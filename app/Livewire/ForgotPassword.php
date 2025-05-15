<?php

namespace App\Livewire;

use App\Jobs\SendSMSJob;
use App\Models\Resident;
use App\Models\SystemLog;
use App\Models\User;
use App\Services\PhilSMSService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;

class ForgotPassword extends Component
{

    public $phone;

    public function render()
    {
        return view('livewire.forgot-password');
    }

    public function checkPhone()
    {
        $validated = $this->validate([
            'phone' => ['required', 'regex:/^0[0-9]{10}$/'],
        ], [
            'phone.required' => 'Please enter your phone number.',
            'phone.regex' => 'The phone number must start with 0 and be 11 digits long.',
        ]);

        $phone = $validated['phone'];

        // Replace the leading 0 with +63
        $formattedPhone = preg_replace('/^0/', '+63', $phone);

        // Now you can query the database
        $resident = Resident::all()->first(function ($res) use ($formattedPhone) {
            try {
                return Crypt::decryptString($res->phone) === $formattedPhone;
            } catch (\Exception $e) {
                return false; // Ignore decryption errors
            }
        });
        if ($resident) {
            $user = User::find($resident->user_id);
            Auth::login($user);
            $verificationCode = random_int(100000, 999999);
            $resident->code = $verificationCode;
            $resident->save();

            $recipient = $formattedPhone;
            $message = 'Hello! Forgot your password? No worries. Here is your verification code: ' . $verificationCode;
//            $response = $this->smsService->sendSMS($recipient, $message);
            SendSMSJob::dispatch($formattedPhone, $message);
            SystemLog::create([
                'transaction_id' => 'None',
                'action' => 'Sent OTP for password reset to ' . $formattedPhone,
                'type' => 'report_update',
            ]);
            return $this->redirect('/resident/enter-code', navigate: true);

//            return redirect()->route('EnterCode');
        } else {
            $this->reset('phone');
            session()->flash('error', 'The phone number you entered was not found in our system.');
            return $this->redirect('/resident/forgot-password', navigate: true);
        }
    }


}
