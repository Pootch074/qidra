<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Mail\RegOtpMail;
use App\Mail\SendOtpMail;
use App\Models\Category;
use App\Models\Division;
use App\Models\Position;
use App\Models\Section;
use App\Models\Step;
use App\Models\User;
use App\Models\Window;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function index()
    {
        // 1️⃣ Load all divisions and positions
        $divisions = Division::orderBy('division_name')->get();
        $positions = Position::orderBy('position_name')->get();
        $roles = User::getAssignableUserTypes();

        // 2️⃣ Determine old input for cascading selects
        $oldDivisionId = old('divisionId');
        $oldSectionId = old('sectionId');
        $oldStepId = old('stepId');
        $oldCategoryId = old('categoryId');

        // 3️⃣ Load sections based on old division
        $sections = $oldDivisionId
            ? Section::where('division_id', $oldDivisionId)->orderBy('section_name')->get()
            : collect();

        // 4️⃣ Load steps based on old section
        $steps = $oldSectionId
            ? Step::where('section_id', $oldSectionId)->orderBy('step_number')->get()
            : collect();

        // 5️⃣ Load categories based on old step
        $categories = $oldStepId
            ? Category::where('step_id', $oldStepId)->orderBy('category_name')->get()
            : collect();

        // 6️⃣ Load windows based on old category
        $windows = $oldCategoryId
            ? Window::where('category_id', $oldCategoryId)->orderBy('window_number')->get()
            : collect();

        // 7️⃣ Return view with all data
        return view('auth.register', compact(
            'divisions', 'sections', 'steps', 'categories', 'windows', 'positions', 'roles'
        ));
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        // Store all registration data INCLUDING password
        $registrationData = [
            'first_name' => $data['firstName'],
            'last_name' => $data['lastName'],
            'division_id' => $data['divisionId'],
            'section_id' => $data['sectionId'],
            'position' => $data['position'],
            'email' => $data['email'],
            'step_id' => $data['stepId'],
            'window_id' => $data['windowId'],
            'category_id' => $data['categoryId'],
            'password' => $data['password'],
            'status' => User::STATUS_INACTIVE,
            'user_type' => User::TYPE_USER,
        ];

        session(['registration_data' => $registrationData]);

        // Generate OTP
        $otp = random_int(100000, 999999);
        $otpExpiresAt = now()->addMinutes(5);

        session([
            'otp_code' => $otp,
            'otp_expires_at' => $otpExpiresAt,
        ]);

        // Send OTP email
        Mail::to($data['email'])->send(
            new RegOtpMail((object) [
                'email' => $data['email'],
                'first_name' => $data['firstName'],
                'otp_code' => $otp,
            ])
        );

        return redirect()
            ->route('register.show.otp');
    }

    public function registerVerifyOtp(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|digits:6',
        ]);

        $registrationData = session('registration_data');
        $otpCode = session('otp_code');
        $otpExpiresAt = session('otp_expires_at');

        if (! $registrationData || ! $otpCode || ! $otpExpiresAt) {
            return redirect()
                ->route('register')
                ->withErrors(['otp_code' => 'OTP session expired. Please register again.']);
        }

        if ($request->otp_code !== (string) $otpCode) {
            return back()->withErrors(['otp_code' => 'Invalid OTP code.']);
        }

        if (now()->greaterThan($otpExpiresAt)) {
            return back()->withErrors(['otp_code' => 'OTP has expired.']);
        }

        /*
         |--------------------------------------------------------------------------
         | Create User (Laravel 12 way)
         |--------------------------------------------------------------------------
         | Password is PLAIN here
         | User::setPasswordAttribute() hashes it automatically
         */
        $user = User::create($registrationData);

        $user->email_is_verified = true;
        $user->save();

        // 🔐 VERY IMPORTANT: Clear sensitive session data
        session()->forget([
            'registration_data',
            'otp_code',
            'otp_expires_at',
        ]);

        return redirect()
            ->route('login')
            ->with('success', 'Account verified. Please wait for admin approval.');
    }

    public function registerShowOtp()
    {
        $registrationData = session('registration_data');
        $otpCode = session('otp_code');
        $otpExpiresAt = session('otp_expires_at');

        if (! $registrationData || ! $otpCode || ! $otpExpiresAt) {
            return redirect()->route('register')->withErrors(['otp_code' => 'OTP session not found or expired.']);
        }

        return view('auth.registerotp', [
            'otpExpiresAt' => $otpExpiresAt->timestamp,
            'email' => $registrationData['email'],
            'first_name' => $registrationData['first_name'],
        ]);
    }

    public function sectionsByDivision(Division $divisionId)
    {
        return response()->json(
            $divisionId->sections()->orderBy('section_name')->get(['id', 'section_name'])
        );
    }

    public function stepsBySection(Section $sectionId)
    {
        return response()->json(
            $sectionId->steps()->orderBy('step_number')->get(['id', 'step_name'])
        );
    }

    public function categoriesByStep(Step $stepId)
    {
        return response()->json(
            $stepId->categories()->get(['id', 'category_name'])
        );
    }

    public function windowsByCategory(Category $categoryId)
    {
        return response()->json(
            $categoryId->windows()->orderBy('window_number')->get(['id', 'window_number'])
        );
    }

    public function resendOtp(Request $request)
    {
        $userId = session('otp_user_id');

        if (! $userId) {
            return redirect()->route('register')->withErrors(['otp_code' => 'OTP session not found.']);
        }

        $user = User::find($userId);

        if (! $user) {
            return redirect()->route('register')->withErrors(['otp_code' => 'User not found.']);
        }

        // Generate new OTP
        $user->otp_code = rand(100000, 999999);
        $user->otp_expires_at = now()->addMinutes(10);
        $user->save();

        // Update session
        session([
            'otp_user_id' => $user->id,
        ]);

        // Send OTP email
        Mail::to($user->email)->send(new SendOtpMail($user));

        return redirect()->route('register.show.otp')->with('success', 'A new OTP has been sent to your email.');
    }
}
