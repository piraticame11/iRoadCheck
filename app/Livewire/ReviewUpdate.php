<?php

namespace App\Livewire;

use AllowDynamicProperties;
use App\Models\Notification;
use App\Models\Report;
use App\Models\Staff;
use App\Models\StaffLog;
use App\Models\Suggestion;
use App\Models\TemporaryUpdate;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

#[AllowDynamicProperties] class ReviewUpdate extends Component
{
    public $report, $nearbyReports;
    public $selectedReports = [];
    protected $rules = [
        'selectedReports' => 'required|array|min:1',
        'selectedStatus' => 'required|string|in:Fixed,Ongoing',
    ];
    public $isOpen = false;

    public function mount()
    {
        $userId = \Auth::id(); // Get authenticated user ID

        if ($userId) {
            // Retrieve the first unopened report for the user
            $this->report = TemporaryUpdate::where('reporter_id', $userId)
                ->where('is_opened', false)
                ->first();


            if ($this->report) {
                // Mark as opened only if it's not already updated
                if (!$this->report->is_opened) {
                    $this->report->update(['is_opened' => true]);
                    $this->isOpen = true;

                    // Get current report location
                    $lat = $this->report->lat;
                    $lng = $this->report->lng;

                    // Fetch nearby defects within 2 meters
                    $this->nearbyReports = Report::selectRaw("
    *,
    ST_Distance_Sphere(
        point(lng, lat),
        point(?, ?)
    ) AS distance
")
                        ->setBindings([$lng, $lat])
                        ->having("distance", "<=", 4) // in meters
                        ->whereIn('status', ['Unfixed', 'Ongoing'])
                        ->orderBy("distance")
                        ->get();

                } else {
                    $this->isOpen = false; // Ensure modal doesn't show again
                }
            } else {
                $this->isOpen = false; // No report, so keep modal closed
            }
        }
    }
    public function closeModal()
    {
        $userId = Auth::id();

        // Find the user's temporary report
        $temporaryUpdate = TemporaryUpdate::where('reporter_id', $userId)->first();

        $this->isOpen = false;
        $temporaryUpdate->delete();
    }
    public function updateDefects($selectedStatus)
    {

        $userId = Auth::id();
        $temporaryUpdate = TemporaryUpdate::where('reporter_id', $userId)->first();

        if (!$temporaryUpdate || !$temporaryUpdate->image) {

            session()->flash('error', 'No update image found.');
            return;
        }


        if ($selectedStatus == "Repaired") {

                $jsonPath = storage_path("app/public/updates/{$temporaryUpdate->image_name}");
                if (!$jsonPath){
                    dd('Could not read json file.');
                }

                $timeout = 10; // Max wait time in seconds
                $startTime = time();

                while (!file_exists($jsonPath) && (time() - $startTime) < $timeout) {
                    usleep(500000); // Wait 0.5 seconds before checking again
                }
                // Make sure file exists before attempting to read it
                if (file_exists($jsonPath)) {
                    $jsonData = json_decode(file_get_contents($jsonPath), true);
                    dd($jsonData);
                    if (!empty($jsonData['prediction'])) {
                        return redirect()->back()->with('dont_allow_update_open', true);
                    } else {

                    }
                }else{
                    return redirect()->back()->with('ai_is_off', true);
                }
        }



        $updateImagePath = $temporaryUpdate->image;

        try {
            DB::beginTransaction();

            // Update selected reports with image and status
            Report::whereIn('id', $this->selectedReports)
                ->update([
                    'updated_image' => $updateImagePath,
                    'status' => $selectedStatus,
                    'updater_id' => $userId,
                    'updated_on' => today(),
                ]);
            Suggestion::where('report_id', $this->selectedReports)
                ->update([
                    'status' => $selectedStatus,
                ]);

            session()->flash('feedback', 'Report submitted successfully!');
            session()->flash('feedback_type', 'success');
        $this->isOpen = false;



            try {
                $reporter = Auth::user();
                if (!$reporter) {
                    throw new \Exception('Authenticated user not found.');
                }
            } catch (\Exception $e) {
                throw new \Exception('Error retrieving reporter: ' . $e->getMessage());
            }

            try {
                $admins = User::where('user_type', 1)->get();
                $staff = Staff::with('user')->get();

                if ($admins->isEmpty() && $staff->isEmpty()) {
                    throw new \Exception('No admins or staff found.');
                }
            } catch (\Exception $e) {
                throw new \Exception('Error fetching admins/staff: ' . $e->getMessage());
            }

            try {
                $firstName = Crypt::decryptString($reporter->first_name);
                $lastName = Crypt::decryptString($reporter->last_name);
            } catch (\Exception $e) {
                $firstName = '[Unknown]';
                $lastName = '';
            }

            $notificationData = [
                'title' => 'Report Updated',
                'message' => "A report has been updated by {$firstName} {$lastName} at {$temporaryUpdate->location}.",
                'is_read' => false,
            ];

            try {
                $this->notifyUsers($admins, $notificationData, User::class);

                if ($reporter->user_type !== 3) {
                    $this->notifyUsers($staff, $notificationData, Staff::class);
                }
            } catch (\Exception $e) {
                Log::warning('Notification to admin/staff failed: ' . $e->getMessage());
            }

            try {
                Notification::create([
                    'title' => 'Report Updated',
                    'message' => "A report has been updated by {$firstName} {$lastName} at {$temporaryUpdate->location}.",
                    'notifiable_id' => $reporter->id,
                    'notifiable_type' => User::class,
                    'is_read' => false,
                ]);
                // Log the update action for auditing purposes
                $user = Auth::user();
                StaffLog::create([
                    'staff_id' => $user->id,
                    'action' => "Updated a report at {$temporaryUpdate->location}",
                    'dateTime' => now(),
                    'user_id' => $user->id,
                ]);
            } catch (\Exception $e) {
                Log::warning('Notification to reporter failed: ' . $e->getMessage());
            }

            DB::commit();
            $temporaryUpdate->delete();
            return $this->redirect('/staff/capture-road-defect', navigate: true);

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Something went wrong while updating reports.');
        }
    }

    private function notifyUsers($users, $notificationData, $notifiableType)
    {
        foreach ($users as $user) {
            Notification::create(array_merge($notificationData, [
                'notifiable_id' => $user->id ?? $user->user_id,
                'notifiable_type' => $notifiableType,
            ]));
//            Log::info("Notification sent to {$notifiableType} ID: {$user->id ?? $user->user_id}");
        }
    }

    public function render()
    {
        return view('livewire.review-update');
    }
}
