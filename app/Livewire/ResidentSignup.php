<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Resident;
use App\Services\PhilSMSService;
use App\Jobs\SendSMSJob;
use Illuminate\Support\Facades\Auth;

class ResidentSignup extends Component
{
    public $step = 1;
    public $first_name = '';
    public $middle_name = '';
    public $last_name = '';
    public $sex = '';
    public $phone = '';
    public $password = '';
    public $confirmPassword = '';
    public $phoneError = '';
    
    // protected $smsService;

    // public function boot(PhilSMSService $smsService)
    // {
    //     $this->smsService = $smsService;
    //     Log::info('ResidentSignup component booted');
    // }

    protected $rules = [
        'first_name' => 'required|string|max:255',
        'middle_name' => 'nullable|string|max:255',
        'last_name' => 'required|string|max:255',
        'sex' => 'required|in:male,female',
        'phone' => 'required|regex:/^0[0-9]{10}$/|size:11',
        'password' => [
            'required', 
            'min:8',
            'regex:/[a-z]/',
            'regex:/[A-Z]/',
            'regex:/[0-9]/',
            'regex:/[!@#$%^&*]/',
        ],
        'confirmPassword' => 'required|same:password',
    ];

    protected $messages = [
        'phone.regex' => 'Phone number must start with 0 and be 11 digits long.',
        'password.regex' => 'Password must include at least one uppercase letter, one lowercase letter, one number, and one special character.',
        'confirmPassword.same' => 'Passwords do not match.',
    ];

    public function checkPhoneExists()
    {
        Log::info('Checking phone existence', ['phone' => $this->phone]);
        
        // No need to check if phone is empty
        if (empty($this->phone)) {
            $this->phoneError = '';
            Log::info('Phone is empty, skipping check');
            return false;
        }

        // Validate phone format before checking if it exists
        if (!preg_match('/^0[0-9]{10}$/', $this->phone)) {
            $this->phoneError = 'Please enter a valid 11-digit phone number starting with 09';
            Log::warning('Invalid phone format', ['phone' => $this->phone]);
            return false;
        }

        // Format phone for comparison with database values
        $formattedPhone = preg_replace('/^0/', '+63', $this->phone);
        Log::info('Formatted phone for database check', ['formatted' => $formattedPhone]);
        
        $phoneExists = false;

        try {
            // Query the database to check if the phone number exists
            $residents = DB::table('residents')->get();
            Log::info('Retrieved residents from database', ['count' => count($residents)]);
            
            foreach ($residents as $resident) {
                try {
                    $decryptedPhone = Crypt::decryptString($resident->phone);
                    Log::debug('Comparing phones', [
                        'decrypted' => $decryptedPhone,
                        'formatted' => $formattedPhone
                    ]);
                    
                    if ($decryptedPhone === $formattedPhone) {
                        $phoneExists = true;
                        Log::info('Phone match found', ['resident_id' => $resident->id]);
                        break;
                    }
                } catch (\Exception $e) {
                    Log::error("Error decrypting phone: " . $e->getMessage(), [
                        'resident_id' => $resident->id,
                        'exception' => get_class($e)
                    ]);
                    continue;
                }
            }

            if ($phoneExists) {
                $this->phoneError = 'Phone number is already registered. Please use another number.';
                Log::warning('Phone already exists', ['phone' => $this->phone]);
                return true;
            } else {
                $this->phoneError = '';
                Log::info('Phone is available for registration');
                return false;
            }
        } catch (\Exception $e) {
            Log::error("Error checking phone existence: " . $e->getMessage(), [
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            $this->phoneError = 'Unable to verify phone number. Please try again.';
            return true;
        }
    }

    public function submitForm()
    {
        Log::info('Submitting registration form', [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone
        ]);
        
        // Dump form data to the JS console for debugging
        $this->dispatch('console-log', [
            'message' => 'Form submission initiated',
            'data' => [
                'step' => $this->step,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'sex' => $this->sex,
                'phone' => $this->phone,
                'phoneError' => $this->phoneError
            ]
        ]);
        
        // Validate all inputs
        try {
            Log::info('Validating form data');
            $this->validate();
            Log::info('Form validation passed');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', [
                'errors' => $e->errors(),
            ]);
            $this->dispatch('console-log', [
                'message' => 'Validation errors',
                'data' => $e->errors()
            ]);
            throw $e;
        }
        
        // Check if phone exists again to be sure
        if ($this->checkPhoneExists()) {
            Log::warning('Phone exists check blocked submission');
            $this->dispatch('console-log', [
                'message' => 'Phone exists, cannot proceed',
                'data' => $this->phoneError
            ]);
            return;
        }
        
        // Format phone number
        $formattedPhone = preg_replace('/^0/', '+63', $this->phone);
        Log::info('Phone formatted for database', ['formatted' => $formattedPhone]);


        
        // Start a database transaction
        DB::beginTransaction();
        Log::info('Starting database transaction');
        
        try {
            // Create user
            Log::info('Creating user record');
            $user = User::create([
                'first_name' => Crypt::encryptString($this->first_name),
                'middle_name' => $this->middle_name ? Crypt::encryptString($this->middle_name) : null,
                'last_name' => Crypt::encryptString($this->last_name),
                'sex' => Crypt::encryptString($this->sex),
                'user_type' => 2, // Resident type
                'password' => bcrypt($this->password),
            ]);
            
            Log::info('User created successfully', ['user_id' => $user->id]);
            
            // Generate a 6-digit code
            $verificationCode = random_int(100000, 999999);
            Log::info('Generated verification code', ['code' => $verificationCode]);
            
            // Create a corresponding resident entry
            Log::info('Creating resident record');
            DB::table('residents')->insert([
                'user_id' => $user->id,
                'phone' => Crypt::encryptString($formattedPhone),
                'code' => $verificationCode,
                'is_activated' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            Log::info('Resident record created');

             // // Send SMS with verification code
             // Set of greeting messages
             $greetings = [
                'Hello! Thanks for signing up for an account. Your verification code is: ',
                'Hi there! We received your request. Your verification code is: ',
                'Greetings! Your verification code is: ',
                'Welcome! Please use the following verification code: ',
                'Hey! Your account setup is almost complete. Use this verification code: ',
            ];
            Log::info('Sending SMS', ['to' => $formattedPhone]);

            // Randomly select a greeting message
            $randomGreeting = $greetings[array_rand($greetings)];

            // Final message
            $message = $randomGreeting . $verificationCode;
            SendSMSJob::dispatch($formattedPhone, $message);
            Log::info('SMS dispatched', ['to' => $formattedPhone]);
            
            // Commit transaction
            DB::commit();
            Log::info('Database transaction committed');
            
            // Log in the user
            Auth::login($user);
            Log::info('User logged in', ['user_id' => $user->id]);
            
            // Dispatch success event to console
            $this->dispatch('console-log', [
                'message' => 'Registration successful, redirecting to verification page',
                'data' => ['user_id' => $user->id]
            ]);
            
            // Redirect to verification page
            return redirect()->route('verify-code');
            
        } catch (\Exception $e) {
            // Rollback transaction if something failed
            DB::rollBack();
            Log::error("Error in registration process: " . $e->getMessage(), [
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            
            $this->dispatch('console-log', [
                'message' => 'Registration failed',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Registration failed. Please try again.');
            return;
        }
    }
    
    public function verifyCode($code)
    {
        Log::info('Verifying code', ['code_length' => strlen($code)]);
        
        // Validate the input
        if (!is_numeric($code) || strlen($code) != 6) {
            Log::warning('Invalid verification code format', ['code' => $code]);
            session()->flash('error', 'The code must be exactly 6 digits.');
            return;
        }
        
        // Check if the verification code is correct
        $user = Auth::user();
        Log::info('Checking code for user', ['user_id' => $user->id]);
        
        $resident = Resident::where('user_id', $user->id)->first();
        
        if ($resident && $resident->code == $code) {
            // Activate the account
            Log::info('Code verification successful, activating account');
            $resident->is_activated = 1;
            $resident->save();
            
            Log::info('Account activated, redirecting to dashboard');
            return redirect()->route('resident.dashboard')->with('success', 'Account activated successfully!');
        } else {
            Log::warning('Code verification failed', [
                'provided_code' => $code,
                'expected_code' => $resident ? $resident->code : null
            ]);
            session()->flash('error', 'The code is incorrect. Please try again.');
            return;
        }
    }

    public function render()
    {
        return view('livewire.resident-signup');
    }
}