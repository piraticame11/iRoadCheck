<?php

namespace App\Livewire\Pages\Resident;

use App\Models\Resident;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Add this line to import the User model
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;
use App\Models\UserType;

class ProfileEdit extends Component
{
    public $profilePicture;
    public $currentProfilePicture;

    public $phone;
    public $current_password;
    public $password;
    public $password_confirmation;
    public $first_name;
    public $middle_name;
    public $last_name;
    public $sex;
    public $date_of_birth;
    public $user_type;
    public $userTypeName;
    public $showModal = false;

    public $resident;

    public function mount(): void
    {
        $user = Auth::user();
        $this->resident = Resident::where('user_id', $user->id)->first();

        $this->first_name = Crypt::decryptString($user->first_name);
        $this->middle_name = $user->middle_name ? Crypt::decryptString($user->middle_name) : null;
        $this->last_name = Crypt::decryptString($user->last_name);
        $this->sex = Crypt::decryptString($user->sex);

        // Initialize phone number from resident model
        if ($this->resident) {
            $this->phone = Crypt::decryptString($this->resident->phone);
            // Reformat from +63 format to 0 format for display
            $this->phone = preg_replace('/^\+63/', '0', $this->phone);
        }

        // Format the date for display (F j, Y) if it exists
        $this->date_of_birth = $user->date_of_birth
            ? Carbon::parse($user->date_of_birth)->format('F j, Y')
            : null;

        $this->user_type = $user->user_type;
        $this->userTypeName = UserType::where('id', $this->user_type)->value('type');
    }

    public function updatedDateOfBirth($value): void
    {
        // Ensure date_of_birth is formatted correctly when updated
        try {
            $this->date_of_birth = Carbon::parse($value)->format('F j, Y');
        } catch (\Exception $e) {
            $this->date_of_birth = null; // Handle invalid date input
        }
    }

    private function resetForm(): void
    {
        $this->password = null;
        $this->password_confirmation = null;
        $this->current_password = null;
    }

    public function updateBasicInfo(): void
    {
        $user = Auth::user();
        if (!$user instanceof User) {
            throw new \Exception('Authenticated user is not an instance of User model.');
        }

        // Validate input fields
        $validated = $this->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'sex' => ['required', 'string', 'in:male,female'],
            'date_of_birth' => ['nullable', 'date'],
        ]);

        $hasChanges = false;
        if (Crypt::decryptString($user->first_name) !== $validated['first_name']) $hasChanges = true;
        if (Crypt::decryptString($user->last_name) !== $validated['last_name']) $hasChanges = true;

        $currentMiddleName = !empty($user->middle_name) ? Crypt::decryptString($user->middle_name) : null;
        if ($currentMiddleName !== $validated['middle_name']) $hasChanges = true;

        if (Crypt::decryptString($user->sex) !== $validated['sex']) $hasChanges = true;

        foreach ($validated as $key => $value) {
            if ($key === 'date_of_birth') {
                $existingDate = $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : null;
                if ($existingDate !== $value) {
                    $hasChanges = true;
                }
            } else {
                if ($user->$key !== $value) {
                    $hasChanges = true;
                }
            }
        }

        if ($hasChanges) {
            try {
                // Update user fields
                $user->update([
                    'first_name' => Crypt::encryptString($validated['first_name']),
                    'middle_name' => $validated['middle_name'] ? Crypt::encryptString($validated['middle_name']) : null,
                    'last_name' => Crypt::encryptString($validated['last_name']),
                    'sex' => Crypt::encryptString($validated['sex']),
                    'date_of_birth' => $validated['date_of_birth']
                        ? Carbon::parse($validated['date_of_birth'])->format('Y-m-d')
                        : null, // Store in Y-m-d format
                ]);

                // Dispatch a success message
                session()->flash('success', 'Basic information updated successfully.');
            } catch (\Exception $e) {
                session(['hideSearchBar' => true]); // Hide search bar
                session()->flash('feedback', 'Something went wrong. Please try again.');
                session()->flash('feedback_type', 'error');
            }
        } else {
            session(['hideSearchBar' => true]); // Hide search bar
            session()->flash('feedback', 'No changes were made to your basic information.');
            session()->flash('feedback_type', 'info');
        }
    }



    public function updateAccountInfo(): void
    {
        $user = Auth::user(); // Get the authenticated user
        if (!$user instanceof User) {
            throw new \Exception('Authenticated user is not an instance of User model.');
        }

        // Determine what needs to be updated
        $isPasswordChanged = !empty($this->password);
        $isCurrentPasswordFilled = !empty($this->current_password);

        // If no changes are detected, reset the form and show a message
        if (!$isPasswordChanged && $isCurrentPasswordFilled) {
            $this->resetForm();
            session(['hideSearchBar' => true]); // Hide search bar
            session()->flash('feedback', 'No changes were made to your account information.');
            session()->flash('feedback_type', 'info');
            return;
        }

        // If no actual changes to phone, or password
        if (!$isPasswordChanged) {
            $this->resetForm();
            session(['hideSearchBar' => true]); // Hide search bar
            session()->flash('feedback', 'No changes were made to your account information.');
            session()->flash('feedback_type', 'info');
            return;
        }

        // Prepare validation rules dynamically
        $rules = ['current_password' => ['required', 'string']]; // Current password is always required

        if ($isPasswordChanged) {
            $rules['password'] = [
                'required',
                'confirmed',
                'min:8',
                'regex:/[A-Z]/',  // At least one uppercase letter
                'regex:/[a-z]/',  // At least one lowercase letter
                'regex:/[0-9]/',  // At least one digit
                'regex:/[@$!%*?&]/' // At least one special character
            ];
            $rules['password_confirmation'] = 'required|same:password';
        }

        // Validate input data
        $validatedData = $this->validate($rules, [
            'current_password.required' => 'The current password is required to update your account information.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'password_confirmation.required' => 'You must confirm your new password.',
            'password_confirmation.same' => 'Password confirmation does not match the new password.',
        ]);

        // Verify the current password
        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'The current password is incorrect.');
            return;
        }

        // Prevent reusing the same password
        if ($isPasswordChanged && Hash::check($this->password, $user->password)) {
            $this->addError('password', 'The new password cannot be the same as the current password.');
            return;
        }

        // Update the user model with changes
        if ($isPasswordChanged) {
            $user->password = Hash::make($this->password);
        }

        $user->save(); // Save changes to the database
        $user->refresh();

        // Reset the form fields
        $this->resetForm();

        // Provide feedback to the user
        session(['hideSearchBar' => true]); // Hide search bar
        session()->flash('feedback', 'Account information updated successfully.');
        session()->flash('feedback_type', 'success');
    }



    public function render()
    {
        return view('livewire.pages.resident.profile-edit');
    }
}
