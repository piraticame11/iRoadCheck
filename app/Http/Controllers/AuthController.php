<?php

namespace App\Http\Controllers;

use App\Models\AdminLog;
use App\Models\ResidentLog;
use App\Models\StaffLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Models\Staff;

class AuthController extends Controller
{
    public function AdminSignIn(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|min:8',
        ]);

        // Find the admin user by user_type = 1 and match the decrypted username
        $adminUser = \App\Models\User::where('user_type', 1)
            ->get()
            ->first(function ($admin) use ($request) {
                return Crypt::decryptString($admin->username) === $request->username;
            });

        // If username is found but password is incorrect
        if ($adminUser) {
            if (Auth::attempt(['email' => $adminUser->email, 'password' => $request->password])) {
                AdminLog::create([
                    'admin_id' => $adminUser->id,
                    'action' => 'Logged out.',
                    'dateTime' => now(),
                    'user_id' => $adminUser->id,
                ]);
                return redirect()->route('admin.dashboard'); // Adjust as needed
            }

            // Password is incorrect
            throw ValidationException::withMessages([
                'password' => 'The provided password is incorrect.',
            ]);
        }

        // Username not found
        throw ValidationException::withMessages([
            'username' => 'No admin account found with this username.',
        ]);
    }

    // public function StaffSignIn(Request $request)
    // {

    //     // Validate the incoming request data for staff and residents
    //     $request->validate([
    //         'username' => 'required', // staff login by username
    //         'password' => 'required|min:8',
    //     ]);

    //     // //kuhaon generated password bycrypt then e convert into password
    //     // // Attempt to log the staff/resident in using username and password

    //     // Get the users with user_type = 3 (Staff)
    //     $users = \App\Models\User::where('user_type', 3)->get();

    //     foreach ($users as $staff) {
    //         try {
    //             if (Crypt::decryptString($staff->username) === $request->username) {
    //                 // Check password directly
    //                 if (Hash::check($request->password, $staff->password)) {
    //                     Auth::loginUsingId($staff->id);
    //                     return redirect()->route('staff.dashboard');
    //                 }
    //             }
    //         } catch (\Exception $e) {
    //             Log::error("Error in staff authentication: " . $e->getMessage());
    //         }
    //     }
    //     // If authentication fails
    //     throw ValidationException::withMessages([
    //         'username' => 'The provided credentials do not match our records.',
    //         'password' => 'The provided password is incorrect.',
    //     ]);
    // }
//    public function StaffSignIn(Request $request)
//    {
//
//        // Validate the incoming request data for staff and residents
//        $request->validate([
//            'username' => 'required', // staff login by email
//            'password' => 'required|min:8',
//        ]);
//
//        //kuhaon generated password bycrypt then e convert into password
//        // Attempt to log the staff/resident in using email and password
//        if (Auth::attempt(['username' => $request->username, 'password' => $request->password, 'user_type' => 3])) {
//            return redirect()->route('staff.dashboard'); // Adjust the route as needed
//        }
//
//        // If authentication fails
//        throw ValidationException::withMessages([
//            'username' => 'The provided credentials do not match our records.',
//            'password' => 'The provided password is incorrect.',
//        ]);
//    }


    // public function AdminSignIn(Request $request)
    // {
    //     // Step 1: Validate inputs
    //     $request->validate([
    //         'username' => 'required|string',
    //         'password' => 'required|string',
    //     ]);

    //     $username = Str::lower($request->input('username'));
    //     $ip = $request->ip();
    //     $key = "admin_login|{$username}|{$ip}";

    //     // Step 2: Check if the user is rate limited
    //     if (RateLimiter::tooManyAttempts($key, 2)) {
    //         $seconds = RateLimiter::availableIn($key);
    //         return back()->withErrors([
    //             'timeout' => $seconds,
    //         ])->withInput();
    //     }

    //     // Step 3: Join with users to find the staff record, including staff status
    //     $admin = DB::table('admins')
    //         ->join('users', 'admins.user_id', '=', 'users.id')
    //         ->where('users.username', $username)
    //         ->where('users.user_type', 1)
    //         ->select('users.id as user_id', 'users.password')
    //         ->first();

    //     if (!$admin) {
    //         return back()->withErrors([
    //             'username' => 'Username not found or not a staff account.',
    //         ])->withInput();
    //     }

    //     // Step 4: Check password manually
    //     if (!\Hash::check($request->password, $admin->password)) {
    //         RateLimiter::hit($key, 45);
    //         return back()->withErrors([
    //             'password' => 'Incorrect password.',
    //         ])->withInput();
    //     }

    //     Auth::loginUsingId($admin->user_id);

    //     RateLimiter::clear($key);
    //     return redirect()->route('admin.dashboard');
    // }


    public function showLoginForm()
    {
        return view('iroadcheck.prototype.Admin.login');  // Adjust to your actual login view
    }

    // Log the Admin user out
    public function Logout()
    {
        $user = Auth::user();
        try {
            AdminLog::create([
                'admin_id' => $user->id,
                'action' => 'Logged out.',
                'dateTime' => now(),
                'user_id' => $user->id,
            ]);
        }
        catch (\Exception $e) {

        }

        Auth::logout();
        return redirect()->route('admin-sign-in-show')->with('success', 'You have been logged out successfully.');
    }


    public function showLoginFormStaff()
    {
        return view('iroadcheck.prototype.Staff.login');  // Adjust to your actual login view
    }

    public function StaffSignIn(Request $request)
    {
        // Step 1: Validate inputs
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = Str::lower($request->input('username'));
        $ip = $request->ip();
        $key = "staff_login|{$username}|{$ip}";

        // Step 2: Check if the user is rate limited
        if (RateLimiter::tooManyAttempts($key, 2)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'timeout' => $seconds,
            ])->withInput();
        }

        // Step 3: Join with users to find the staff record, including staff status
        $staff = collect(DB::table('staffs')
        ->join('users', 'staffs.user_id', '=', 'users.id')
        ->where('users.user_type', 3)
        ->select('users.id as user_id', 'users.username', 'users.password', 'staffs.status')
        ->get())
        ->first(function ($user) use ($username) {
            try {
                return Str::lower(Crypt::decryptString($user->username)) === $username;
            } catch (\Exception $e) {
                return false; // Skip decryption failures
            }
        });

        if (!$staff) {
            return back()->withErrors([
                'username' => 'Username not found or not a staff account.',
            ])->withInput();
        }

        if ($staff->status === 'inactive') {
            return back()->withErrors([
                'username' => 'This staff account is inactive. Please contact the administrator.',
            ])->withInput();
        }

        // Step 4: Check password manually
        if (!Hash::check($request->password, $staff->password)) {
            RateLimiter::hit($key, 45);
            return back()->withErrors([
                'password' => 'Incorrect password.',
            ])->withInput();
        }

        // Step 5: Log the user in by ID
        Auth::loginUsingId($staff->user_id);

        // Step 6: Check if password needs to be changed after login
        $user = Auth::user(); // get logged in user
        try {
            $user = Auth::user();
            StaffLog::create([
                'staff_id' => $user->id,
                'action' => "Logged in",
                'dateTime' => now(),
                'user_id' => $user->id,
            ]);
        }
        catch (\Exception $e) {

        }
        if ($user->must_change_password) {
            return redirect()->route('staff.password.change');
        }

        RateLimiter::clear($key);
        return redirect()->route('staff.dashboard');
    }


    public function showChangePasswordForm()
    {
        return view('iroadcheck.prototype.Staff.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => [
                'required',
                'string',
                'confirmed',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*]).+$/',
            ],
        ], [
            'password.regex' => 'Password must include at least 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character (!@#$%^&*).',
        ]);

        $user = Auth::user();
        $user->password = bcrypt($request->password);
        $user->must_change_password = false;
        $user->save();

        return redirect()->route('staff.dashboard')->with('success', 'Password changed successfully!');
    }



    public function LogoutStaff()
    {
        try {
            $user = Auth::user();
            StaffLog::create([
                'staff_id' => $user->id,
                'action' => "Logged out",
                'dateTime' => now(),
                'user_id' => $user->id,
            ]);
        }
        catch (\Exception $e) {

        }
        Auth::logout(); // Log the user out
        return redirect()->route('staff-sign-in-show')->with('success', 'You have been logged out successfully.');
    }

    public function LogoutResident()
    {
        $user = Auth::user();
        try {
            ResidentLog::create([
                'resident_id' => $user->id,
                'action' => "Logged out",
                'dateTime' => now(),
            ]);
        }
        catch (\Exception $e) {}
        Auth::logout(); // Log the user out
        return redirect()->route('residents-login')->with('success', 'You have been logged out successfully.');
    }
    public function loginpage(){
        return view('iroadcheck.prototype.residents.login');
    }
}
