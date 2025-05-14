<?php

namespace App\Livewire;


use App\Models\Notification;
use App\Models\Report;
use App\Models\Staff;
use App\Models\Suggestion;
use App\Models\SystemLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use App\Models\TemporaryReport;
use Illuminate\Support\Facades\Session;

class ReviewReport extends Component
{
    public $report;
    public $isOpen = false;

    public function mount()
    {
        $userId = \Auth::id(); // Get authenticated user ID

        if ($userId) {
            // Retrieve the first unopened report for the user
            $this->report = TemporaryReport::where('reporter_id', $userId)
                ->where('is_opened', 0) // Ensure it's actually false (or use 0)
                ->first();

            if ($this->report) {
                // Mark as opened only if it's not already updated
                if (!$this->report->is_opened) {
                    $this->report->update(['is_opened' => 1]); // Update column
                    $this->isOpen = true; // Open the modal
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
        $temporaryReport = TemporaryReport::where('reporter_id', $userId)->first();

        $this->isOpen = false;
        $temporaryReport->delete();
    }

    public function submitReport()
    {
        $userId = Auth::id();

        // Find the user's temporary report
        $temporaryReport = TemporaryReport::where('reporter_id', $userId)->first();



        if ($temporaryReport) {
            // Check if report already exists
            $existingReport = DB::table('reports')
                ->where('reporter_id', '!=', $userId)
                ->where('defect', $temporaryReport->defect)
                ->where('status', 'Unfixed')
                ->whereRaw(
                    "ST_Distance_Sphere(point(lng, lat), point(?, ?)) <= 1",
                    [$temporaryReport->lng, $temporaryReport->lat]
                )
                ->orderByRaw(
                    "ST_Distance_Sphere(point(lng, lat), point(?, ?)) ASC",
                    [$temporaryReport->lng, $temporaryReport->lat]
                )
                ->first();


            if (!$existingReport) {

                // Create a new record in the reports table
                $report = Report::create([
                    'reporter_id' => $temporaryReport->reporter_id,
                    'defect' => $temporaryReport->defect,
                    'lat' => $temporaryReport->lat,
                    'lng' => $temporaryReport->lng,
                    'location' => $temporaryReport->location,
                    'street' => $temporaryReport->street,
                    'purok' => $temporaryReport->purok,
                    'barangay' => $temporaryReport->barangay,
                    'date' => $temporaryReport->date,
                    'time' => $temporaryReport->time,
                    'severity' => $temporaryReport->severity,
                    'image' => $temporaryReport->image,
                    'image_annotated' => $temporaryReport->image_annotated,
                    'status' => $temporaryReport->status,
                    'label' => $temporaryReport->label,
                ]);
                $reporter = Auth::user();

                // ✅ Fetch Admins and Staff
                $admins = User::where('user_type', 1)->get();
                $staff = Staff::with('user')->get();

                if ($admins->isEmpty() && $staff->isEmpty()) {
                    throw new \Exception('No admins or staff found.');
                }

                // ✅ Admin & Staff Notification
                $notificationData = [
                    'report_id' => $report->id,
                    'title' => 'Report Created',
                    'message' => "A new report has been submitted by {$reporter->name} at {$temporaryReport->location}.",
                    'is_read' => false,
                ];

                // ✅ Notify Admins
                $this->notifyUsers($admins, $notificationData, User::class);

                // ✅ Notify Staff only if the reporter is NOT a staff member
                if ($reporter->user_type !== 3) {
                    $this->notifyUsers($staff, $notificationData, Staff::class);
                }

                // ✅ Reporter Notification - Corrected Message
                Notification::create([
                    'report_id' => $report->id,
                    'title' => 'Report Submitted',
                    'message' => "You submitted a new road issue successfully at {$temporaryReport->location}.",
                    'notifiable_id' => $reporter->id,
                    'notifiable_type' => User::class,
                    'is_read' => false,
                ]);

                // Optionally delete the temporary report after copying
                $temporaryReport->delete();
                $this->isOpen = false;
                session()->flash('feedback', 'Report submitted successfully!');
                session()->flash('feedback_type', 'success');
            }
            //
            else{
                try {
                    DB::beginTransaction();
                    try {
                        $suggest = Suggestion::create([
                            'report_id' => $existingReport->id,
                            'reporter_id' => $userId,
                            'is_match' => true,
                            'response_deadline' => now()->addDays(5),
                            'defect' => $existingReport->defect,
                            'lat' => $existingReport->lat,
                            'lng' => $existingReport->lng,
                            'location' => $existingReport->location,
                            'street' => $existingReport->street,
                            'purok' => $existingReport->purok,
                            'barangay' => $existingReport->barangay,
                            'date' => now()->format('Y-m-d'),
                            'time' => now()->format('H:i:s'),
                            'severity' => $existingReport->severity,
                            'label' => $existingReport->label,
                            'image' => $temporaryReport->image ?? null,
                            'image_annotated' => $temporaryReport->image_annotated ?? null,
                            'status' => "Unfixed"
                        ]);
                    } catch (\Exception $e) {
                        throw new \Exception('Failed to create suggestion: ' . $e->getMessage());
                    }

                    try {
                        $existingReport->report_count++;

                        if ($existingReport->report_count >= 20) {
                            $existingReport->label = 4;
                            $existingReport->severity = 4;

                            SystemLog::create([
                                'transaction_id' => $existingReport->id,
                                'user_id' => Auth::id(),
                                'action' => 'Report ID ' . $existingReport->id . ' reached 10+ reports. Label and severity set to 4.',
                                'type' => 'report_update',
                            ]);

                        } elseif ($existingReport->report_count >= 15) {
                            $existingReport->label = 3;
                            $existingReport->severity = 3;

                            SystemLog::create([
                                'transaction_id' => $existingReport->id,
                                'user_id' => Auth::id(),
                                'action' => 'Report ID ' . $existingReport->id . ' reached 5+ reports. Label and severity set to 3.',
                                'type' => 'report_update',
                            ]);

                        } elseif ($existingReport->report_count >= 10) {
                            $existingReport->label = 2;
                            $existingReport->severity = 2;

                            SystemLog::create([
                                'transaction_id' => $existingReport->id,
                                'user_id' => Auth::id(),
                                'action' => 'Report ID ' . $existingReport->id . ' reached 3+ reports. Label and severity set to 2.',
                                'type' => 'report_update',
                            ]);

                        } elseif ($existingReport->report_count >= 5) {
                            $existingReport->label = 1;
                            $existingReport->severity = 1;

                            SystemLog::create([
                                'transaction_id' => $existingReport->id,
                                'user_id' => Auth::id(),
                                'action' => 'Report ID ' . $existingReport->id . ' received first report. Label and severity set to 1.',
                                'type' => 'report_update',
                            ]);
                        }
                        $existingReport->save();

                    } catch (\Exception $e) {
                        throw new \Exception('Error updating report count: ' . $e->getMessage());
                    }


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
                        'report_id' => $suggest->id,
                        'title' => 'Report Created',
                        'message' => "A new report has been submitted by {$firstName} {$lastName} at {$temporaryReport->location}.",
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
                            'report_id' => $suggest->id,
                            'title' => 'Report Submitted',
                            'message' => "You submitted a new road issue successfully at {$temporaryReport->location}.",
                            'notifiable_id' => $reporter->id,
                            'notifiable_type' => User::class,
                            'is_read' => false,
                        ]);
                    } catch (\Exception $e) {
                        Log::warning('Notification to reporter failed: ' . $e->getMessage());
                    }

                    try {
                        $temporaryReport->delete();
                    } catch (\Exception $e) {
                        Log::warning('Failed to delete temporary report: ' . $e->getMessage());
                    }

                    DB::commit();
                    session()->flash('feedback', 'Report submitted successfully!');
                    session()->flash('feedback_type', 'success');
                    return $this->redirect('/residents/report-road-issue', navigate: true);

                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('SuggestionSubmit Error: ' . $e->getMessage());

                    session()->flash('error', 'Something went wrong while submitting the report.');
                    return $this->redirect('/residents/report-road-issue', navigate: true);
                }

            }




//            return redirect()->back()->with('success', 'Report copied successfully');

        }

        return response()->json(['error' => 'No temporary report found'], 404);
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
        return view('livewire.review-report');
    }
}

