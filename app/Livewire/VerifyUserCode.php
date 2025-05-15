<?php

namespace App\Livewire;

use App\Jobs\SendSMSJob;
use App\Models\Resident;
use App\Models\SystemLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class VerifyUserCode extends Component
{
    public $code;

    public function render()
    {
        return view('livewire.verify-user-code');
    }
    public function verifyCode()
    {
        // Validate the input
        $this->validate([
            'code' => 'required|digits:6', // Ensure the code is exactly 6 digits
        ]);

        // Get the input code from the form
        $verificationCode = $this->code;

        // Check if the verification code is correct (this depends on your logic, e.g., stored in the database or session)
        $user = Auth::user();
        $resident = Resident::where('user_id', $user->id)->first();

        if ($resident && $resident->code == $verificationCode) {
            // Update the user's activation status or take any further action

            $resident->is_activated = 1;
            $resident->save();

            // Optionally, you can log the user in or redirect them to the dashboard
            session()->flash('success', 'Account activated successfully!');  // Set session message
            return $this->redirect(route('resident.dashboard'), navigate: true);  // Redirect and trigger frontend navigation

        } else {
            // Handle the case where the code is invalid
//            session()->flash('error', 'The verification code is incorrect. Please try again.');
            $this->code = null;
            return back()->with(['error' => 'The code is incorrect. Please try again.']);
        }
    }
    public function resendCode()
    {
        $resident = auth()->user()->resident;

        if (!$resident) {
            $this->addError('error', 'Resident information not found.');
            return;
        }

        $now = Carbon::now();
        $lastUpdated = Carbon::parse($resident->updated_at);
        $secondsPassed = $lastUpdated->diffInSeconds($now, false); // false = negative if $now < $lastUpdated

        if ($secondsPassed < 180) {
            $minutesLeft = ceil((180 - $secondsPassed) / 60);
            session()->flash('error', "Please wait {$minutesLeft} minute" . ($minutesLeft > 1 ? 's' : '') . " before resending the code.");
            $this->code = null;
            return;

        }

        // Resend logic here
        $verificationCode = rand(100000, 999999);
        $resident->code = $verificationCode;
        $residentPhone = $resident->phone;
        $greetings = [
            'Hello! Thanks for signing up for an account. Your verification code is: ',
            'Hi there! We received your request. Your verification code is: ',
            'Greetings! Your verification code is: ',
            'Welcome! Please use the following verification code: ',
            'Hey! Your account setup is almost complete. Use this verification code: ',
        ];

        // Randomly select a greeting message
        $randomGreeting = $greetings[array_rand($greetings)];

        // Final message
        $message = $randomGreeting . $verificationCode;
        SendSMSJob::dispatch($residentPhone, $message);
        SystemLog::create([
            'transaction_id' => 'None',
            'action' => 'Sent OTP to ' . $residentPhone,
            'type' => 'report_update',
        ]);
        $resident->save();

        $this->code = null;
        session()->flash('success', 'Verification code resent successfully.');
    }
}
