<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Step;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DisplayController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.display.index');
    }

    public function getStepsBySectionId()
{
    try {
        $user = Auth::user();

        // Front desk section ID
        $frontDeskSectionId = Section::where('section_name', 'CRISIS INTERVENTION SECTION')->value('id');
        $applyCategoryFilter = $user->section_id === $frontDeskSectionId;

        // Initial steps for front desk special filter
        $initialStepIds = Step::whereIn('step_number', [1, 2])->pluck('id')->toArray();

        // Fetch steps with categories and windows
        $steps = Step::with(['categories.windows'])
            ->where('section_id', $user->section_id)
            ->orderBy('step_number')
            ->get();

        // Fetch today's serving transactions in user's section
        $transactionsQuery = Transaction::where('section_id', $user->section_id)
            ->where('queue_status', 'serving')
            ->whereDate('created_at', now());

        if ($applyCategoryFilter) {
            $transactionsQuery->where(function ($q) use ($user, $initialStepIds) {
                $q->where(function ($q2) use ($user, $initialStepIds) {
                    $q2->whereIn('step_id', $initialStepIds)
                        ->where(function ($sub) use ($user) {
                            $sub->where('client_type', $user->assigned_category)
                                ->orWhere('client_type', 'deferred');
                        });
                })->orWhereNotIn('step_id', $initialStepIds);
            });
        }

        $transactions = $transactionsQuery->get();

        // Build steps + windows + first transaction per window
        $data = $steps->map(function ($step) use ($transactions) {
            return [
                'step_number' => $step->step_number,
                'step_name' => $step->step_name,
                'windows' => $step->categories->flatMap(function ($cat) use ($transactions) {
                    return $cat->windows->map(function ($win) use ($transactions) {
                        // Assign first available transaction for this step
                        $firstTx = $transactions->where('step_id', $win->category->step_id)->first();

                        $txArray = $firstTx ? [[
                            'id' => $firstTx->id,
                            'queue_number' => strtoupper(substr($firstTx->client_type, 0, 1)) .
                                str_pad($firstTx->queue_number, 3, '0', STR_PAD_LEFT),
                            'client_type' => $firstTx->client_type,
                        ]] : [];

                        return [
                            'window_id' => $win->id,
                            'window_number' => $win->window_number,
                            'transactions' => collect($txArray),
                        ];
                    });
                })->values(),
            ];
        });

        return response()->json($data);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Server Error',
            'message' => $e->getMessage(),
        ], 500);
    }
}



    public function getLatestTransaction()
    {
        $user = Auth::user();

        $frontDeskSectionId = Section::where('section_name', 'CRISIS INTERVENTION SECTION')->value('id');
        $initialStepIds = Step::whereIn('step_number', [1, 2])->pluck('id')->toArray();

        $query = Transaction::with(['step', 'window'])
            ->where('queue_status', 'serving')
            ->whereDate('created_at', now())
            ->whereHas('step', function ($q) use ($user) {
                $q->where('section_id', $user->section_id);
            });

        if ($user->section_id === $frontDeskSectionId) {
            $query->where(function ($q) use ($user, $initialStepIds) {
                $q->where(function ($q2) use ($user, $initialStepIds) {
                    $q2->whereHas('step', function ($s) use ($initialStepIds) {
                        $s->whereIn('id', $initialStepIds);
                    })->where(function ($sub) use ($user) {
                        $sub->where('client_type', $user->assigned_category)
                            ->orWhere('client_type', 'deferred');
                    });
                })->orWhereHas('step', function ($s) use ($initialStepIds) {
                    $s->whereNotIn('id', $initialStepIds);
                });
            });
        }

        $txs = $query->orderBy('updated_at', 'desc')->get();

        if ($txs->isEmpty()) {
            return response()->json([]);
        }

        return response()->json($txs->map(function ($tx) {
            return [
                'id' => $tx->id,
                'queue_number' => $tx->queue_number,
                'client_type' => $tx->client_type,
                'step_number' => $tx->step->step_number ?? null,
                'window_number' => $tx->window->window_number ?? null,
                'recall_count' => $tx->recall_count ?? 0,
            ];
        }));
    }
}
