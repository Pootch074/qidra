<?php

namespace App\Http\Controllers;

use App\Models\Step;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserAssignmentLog;
use App\Models\Window;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreStepRequest;


class AdminController extends Controller
{
    /** Admin Active Users */
    public function index()
    {
        $user = Auth::user();
        $sectionId = $user->section_id;

        // ✅ Transaction counts filtered by user's section and only for today
        $waitingCount = Transaction::where('queue_status', 'waiting')
            ->where('section_id', $sectionId)
            ->whereDate('created_at', now())
            ->count();

        $pendingCount = Transaction::where('queue_status', 'pending')
            ->where('section_id', $sectionId)
            ->whereDate('created_at', now())
            ->count();

        $servingCount = Transaction::where('queue_status', 'serving')
            ->where('section_id', $sectionId)
            ->whereDate('created_at', now())
            ->count();

        $priorityCount = Transaction::where('client_type', 'priority')
            ->where('section_id', $sectionId)
            ->whereDate('created_at', now())
            ->count();

        $regularCount = Transaction::where('client_type', 'regular')
            ->where('section_id', $sectionId)
            ->whereDate('created_at', now())
            ->count();

        $returneeCount = Transaction::where('client_type', 'deferred')
            ->where('section_id', $sectionId)
            ->whereDate('created_at', now())
            ->count();

        $completedCount = Transaction::where('queue_status', 'completed')
            ->where('section_id', $sectionId)
            ->whereDate('created_at', now())
            ->count();

        // ✅ Users in the same section
        $userColumns = [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'position' => 'Position',
            'user_type' => 'User Type',
            'assigned_category' => 'Category',
        ];

        $users = User::where('section_id', $sectionId)
            ->latest()
            ->get();

        // ✅ Transactions filtered by section, only today, ordered by latest queue_number
        $transactions = Transaction::where('section_id', $sectionId)
            ->whereDate('created_at', now())
            ->orderBy('queue_number', 'desc')
            ->get();

        return view('admin.index', compact(
            'transactions',
            'users',
            'userColumns',
            'waitingCount',
            'pendingCount',
            'servingCount',
            'priorityCount',
            'regularCount',
            'returneeCount',
            'completedCount'
        ));
    }

    public function activeUsers()
    {
        $authUser = Auth::user();
        $sectionId = $authUser->section_id;

        $users = User::with(['step', 'window'])
            ->where('section_id', $sectionId)
            ->where('status', 1)
            ->orderBy('user_type')
            ->get();

        $steps = Step::where('section_id', $sectionId)->get();
        $windows = Window::all();
        $userTypes = User::getUserTypes(); // ✅ roles source

        return view('admin.users.active', compact(
            'users',
            'steps',
            'windows',
            'userTypes'
        ));
    }

    public function usersJson()
    {
        $authUser = Auth::user();
        $sectionId = $authUser->section_id;

        $users = User::with(['step', 'window'])
            ->where('section_id', $sectionId)
            ->where('user_type', '!=', 1)
            ->orderBy('id', 'asc')
            ->get();

        $formatted = $users->map(function ($u) {
            return [
                'id' => $u->id,
                'first_name' => $u->first_name,
                'last_name' => $u->last_name,
                'email' => $u->email,
                'position' => $u->position,
                'user_type_name' => $u->getUserTypeTextAttribute(),
                'assigned_category' => $u->assigned_category,
                'step_number' => $u->step?->step_number,
                'window_number' => $u->window?->window_number,
            ];
        });

        return response()->json($formatted);
    }

    public function store(Request $request)
    {
        $authUser = Auth::user();
        $sectionId = $authUser->section_id;

        $rules = [
            'first_name' => 'required|string|max:255|regex:/^[A-Za-z\s\'-]+$/',
            'last_name' => 'required|string|max:255|regex:/^[A-Za-z\s\'-]+$/',
            'email' => 'required|email|unique:users,email',
            'position' => 'required|string|max:255',
            'assigned_category' => $sectionId == 15
                ? 'required|string|in:regular,priority,both'
                : 'nullable|string',
            'step_id' => 'required|exists:steps,id',
            'window_id' => 'required|exists:windows,id',
            'password' => [
                'required',
                'string',
                'min:12',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&]/',
            ],
        ];

        $messages = [
            'first_name.regex' => 'First name may only contain letters, spaces, apostrophes, or hyphens.',
            'last_name.regex' => 'Last name may only contain letters, spaces, apostrophes, or hyphens.',
            'password.min' => 'Password must be at least 12 characters long.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@ $ ! % * ? &).',
        ];

        $validated = $request->validate($rules, $messages);

        $validated['user_type'] = 5; // always TYPE_USER

        if ($sectionId != 15) {
            $validated['assigned_category'] = 'both';
        }

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'position' => $validated['position'],
            'user_type' => $validated['user_type'],
            'assigned_category' => $validated['assigned_category'],
            'step_id' => $validated['step_id'],
            'window_id' => $validated['window_id'] ?? null,
            'section_id' => $sectionId,
            'password' => $validated['password'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'position' => $user->position,
                'user_type_name' => $user->getUserTypeTextAttribute(),
                'assigned_category' => $user->assigned_category,
                'window_number' => $user->window->window_number ?? null,
                'step_number' => $user->step->step_number ?? null,
            ],
        ]);
    }

    public function destroy(User $user)
    {
        try {
            $user->forceDelete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /** Admin Pending Users */
    public function pendingUsers()
    {
        $authUser = Auth::user();
        $sectionId = $authUser->section_id;

        $users = User::with(['step', 'window'])
            ->where('section_id', $sectionId)
            ->where('status', 0)
            ->latest()
            ->get();

        return view('admin.users.pending', [
            'users' => $users,
            'usertypes' => User::getAssignableUserTypes(),
            'steps' => Step::where('section_id', $sectionId)->get(),
        ]);
    }

    public function updateStatus(Request $request, User $user)
    {
        $request->validate([
            'user_type' => 'required|integer|in:0,1,2,3,5,6', // validate allowed constants
        ]);

        $user->user_type = $request->user_type;
        $user->status = User::STATUS_ACTIVE; // set status to 1
        $user->save();

        return response()->json(['success' => true]);
    }

    public function getWindowsByStep(Step $step)
    {
        return response()->json([
            'windows' => $step->windows()->select('id', 'window_number')->get(),
        ]);
    }

    public function updateAssignment(Request $request, User $user)
    {
        try {
            // 1️⃣ Validate input
            $validated = $request->validate([
                'role' => 'required|integer|in:1,2,3,5,6', // match User::getUserTypes() keys
                'step_id' => 'nullable|exists:steps,id',
                'window_id' => 'nullable|exists:windows,id',
                'assigned_category' => 'nullable|in:regular,priority,both',
            ]);

            // 2️⃣ Save current values before update
            $roleBefore = $user->user_type;
            $stepBefore = $user->step_id;
            $windowBefore = $user->window_id;

            // 3️⃣ Update user record
            $user->update([
                'user_type' => $validated['role'],
                'step_id' => $validated['step_id'] ?? null,
                'window_id' => $validated['window_id'] ?? null,
                'assigned_category' => $validated['assigned_category'] ?? null,
            ]);

            // 4️⃣ Determine which fields actually changed
            $changes = [];

            if ($roleBefore != $validated['role']) {
                $changes['role_before'] = (string) $roleBefore;
                $changes['role_after'] = (string) $validated['role'];
            }

            if ($stepBefore != ($validated['step_id'] ?? null)) {
                $changes['step_before'] = $stepBefore !== null ? (string) $stepBefore : null;
                $changes['step_after'] = isset($validated['step_id']) ? (string) $validated['step_id'] : null;
            }

            if ($windowBefore != ($validated['window_id'] ?? null)) {
                $changes['window_before'] = $windowBefore !== null ? (string) $windowBefore : null;
                $changes['window_after'] = isset($validated['window_id']) ? (string) $validated['window_id'] : null;
            }

            // 5️⃣ Log the changes if any
            if (! empty($changes)) {
                UserAssignmentLog::create(array_merge([
                    'section_id' => $user->section_id,
                    'admin_id' => Auth::id(),
                    'target_user_id' => $user->id,
                    'assignment_id' => $user->id, // required field
                ], $changes));
            }

            // 6️⃣ Return success response
            return response()->json([
                'success' => true,
                'message' => 'User updated successfully!',
                'user' => $user->load('step', 'window'),
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function fetchSteps()
    {
        $sectionId = Auth::user()->section_id;
        $steps = Step::where('section_id', $sectionId)
            ->orderBy('step_number', 'asc')
            ->get();

        return view('admin.steps.table', compact('steps'));
    }
    public function storeStep(StoreStepRequest $request)
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
}
