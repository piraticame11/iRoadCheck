<?php

namespace App\Livewire;

use AllowDynamicProperties;
use App\Models\Report;
use App\Models\Suggestion;
use App\Models\TemporaryUpdate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
                        ->having("distance", "<=", 15) // in meters
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

            DB::commit();
            session()->flash('feedback', 'Report submitted successfully!');
            session()->flash('feedback_type', 'success');
        $this->isOpen = false;
            $temporaryUpdate->delete();
            return $this->redirect('/staff/capture-road-defect', navigate: true);

        } catch (\Exception $e) {
            dd('Something went wrong while updating the report.');
            DB::rollBack();
            session()->flash('error', 'Something went wrong while updating reports.');
        }
    }



    public function render()
    {
        return view('livewire.review-update');
    }
}
