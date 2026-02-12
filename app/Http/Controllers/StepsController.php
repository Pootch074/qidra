<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStepRequest;
use App\Http\Requests\UpdateStepRequest;
use App\Models\Step;
use App\Models\Window;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StepsController extends Controller
{
    public function store(StoreStepRequest $request)
    {
        $user = Auth::user();
        $step = DB::transaction(function () use ($user, $request) {
            $latestStep = Step::where('section_id', $user->section_id)
                ->lockForUpdate() // ensures no duplicate step numbers
                ->max('step_number');
            $nextStepNumber = $latestStep ? $latestStep + 1 : 1;

            $step = Step::create([
                'section_id' => $user->section_id,
                'step_number' => $nextStepNumber,
                'step_name' => $request->step_name,
            ]);

            Window::create([
                'window_number' => 1,
                'step_id' => $step->id,
            ]);

            return $step;
        });

        return redirect()
            ->route('fetch.steps')
            ->with('success', "Step '{$step->step_name}' and its first window added successfully.");
    }

    public function update(UpdateStepRequest $request, $id)
    {
        DB::transaction(function () use ($request, $id) {
            $step = Step::findOrFail($id);
            $step->update(['step_name' => $request->step_name]);
        });

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $step = Step::findOrFail($id);

                // Delete all associated windows first (if cascading not enabled)
                $step->windows()->delete();

                $step->delete();
            });

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete step.',
            ], 500);
        }
    }

    public function check($sectionId, $stepNumber)
    {
        $exists = Step::where('section_id', $sectionId)
            ->where('step_number', $stepNumber)
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    public function checkName($sectionId, $stepName)
    {
        $exists = Step::where('section_id', $sectionId)
            ->where('step_name', $stepName)
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    public function steps()
    {
        $sectionId = Auth::user()->section_id;
        $steps = Step::where('section_id', $sectionId)
            ->orderBy('step_number', 'asc')
            ->get();

        return view('admin.steps.table', compact('steps'));
    }
}
