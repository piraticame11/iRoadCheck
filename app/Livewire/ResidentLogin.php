<?php

namespace App\Livewire;

use App\Models\ResidentLog;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Resident;

class ResidentLogin extends Component
{
    public $phone = '';
    public $password = '';

    public function login()
{
    $this->validate([
        'phone' => 'required|regex:/^0\d{10}$/', // Validate as a Philippine phone number
        'password' => 'required|min:6',
    ]);

    $phone = $this->phone;
    $formattedPhone = preg_replace('/^0/', '+63', $phone);

    // Log what phone we're looking for
    Log::info('Looking for phone: ' . $formattedPhone);

    // Retrieve all residents and decrypt their phone numbers for comparison
    $residents = DB::table('residents')->get();
    Log::info('Found ' . $residents->count() . ' residents to check');

    $foundMatch = false;

    foreach ($residents as $resident) {
        try {
            $decryptedPhone = Crypt::decryptString($resident->phone);
            Log::info('Comparing with: ' . $decryptedPhone . ' for user_id: ' . $resident->user_id);

            if ($decryptedPhone === $formattedPhone) {
                $foundMatch = true;
                Log::info('Match found! Attempting auth for user_id: ' . $resident->user_id);

                // Get user to verify it exists
                $user = DB::table('users')->where('id', $resident->user_id)->first();
                if (!$user) {
                    Log::error('User not found for user_id: ' . $resident->user_id);
                    session()->flash('error', 'User account not found.');
                    return;
                }

                // Attempt to log in using the related user's credentials
                if (Auth::attempt(['id' => $resident->user_id, 'password' => $this->password])) {
                    Log::info('Auth successful!');

                    // Check if the account is activated
                    if ($resident->is_activated == 1) {
                        try {
                            ResidentLog::create([
                                'resident_id' => $user->id,
                                'action' => "Logged in",
                                'dateTime' => now(),
                            ]);
                        }
                        catch (\Exception $e) {}
                        Log::info('Account is activated, redirecting to dashboard');
                        return $this->redirect('/resident/dashboard', navigate: true);
                    } else {
                        Log::info('Account not activated, redirecting to verification');
                        return redirect()->route('verify-code')->with('error', 'Please verify your account.');
                    }
                } else {
                    Log::error('Auth failed for user_id: ' . $resident->user_id);
                    session()->flash('error', 'Invalid phone or password.');
                    return;
                }
            }
        } catch (\Exception $e) {
            Log::warning('Decryption error for resident ID: ' . $resident->id . ' - ' . $e->getMessage());
            // Ignore decryption errors (e.g., if the phone is not encrypted)
            continue;
        }
    }

    if (!$foundMatch) {
        Log::error('No matching phone found for: ' . $formattedPhone);
        session()->flash('error', 'Phone number not found.');
    }
}

    public function render(): Factory|View|Application|\Illuminate\View\View
    {
        return view('livewire.resident-login');
    }
}
