<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Step;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

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

        // Fetch all steps for the user's section
        $steps = Step::with('categories.windows')
            ->where('section_id', $user->section_id)
            ->orderBy('step_number')
            ->get();

        $data = $steps->map(function ($step) use ($user) {

            // Determine which categories to pick for this step
            $categoryNames = match($step->step_number) {
                1, 2 => [$user->assigned_category], // use user assigned category
                3, 4 => ['both'],                   // always 'both' for Step 3 & 4
                default => [],
            };

            // Filter categories for this step
            $filteredCategories = $step->categories->filter(fn($cat) => in_array($cat->category_name, $categoryNames));

            // Flatten windows under filtered categories
            $windows = $filteredCategories->flatMap(fn($cat) => $cat->windows)
                ->values()
                ->map(fn($win) => [
                    'window_id' => $win->id,
                    'window_number' => $win->window_number,
                    'transactions' => [], // assign transactions later if needed
                ]);

            return [
                'step_number' => $step->step_number,
                'step_name' => $step->step_name,
                'windows' => $windows,
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
