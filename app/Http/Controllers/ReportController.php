<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Report;
use App\Models\Suggestion;
use App\Models\Staff;
use App\Models\TemporaryReport;
use App\Models\TemporaryUpdate;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
//    public function updateStatus(Request $request)
//    {
//        // Validate the Request Data
//        $request->validate([
//            'newStatus' => 'required|string', // Example status options
//            'reportId' => 'required|exists:reports,id', // Ensure the report exists
//        ]);
//
//        //Fetch the Report from the Database
//        $report = Report::findOrFail($request->input('reportId'));
//
//        // Store the previous status before updating
//        $previousStatus = $report->status;
//
//        //Update the Report's Status
//        $report->status = $request->input('newStatus');
//        $report->save();
//
//        //Reporter Data ex. reporter_id, name, user type, role-if exist or not,
//        // Get the authenticated staff details
//        $staff = auth()->user()->staff; // Assuming `auth()->user()->staff` fetches the staff model
//        $staffName = $staff->username ?? 'Unknown staff';
//        $staffRole = $staff->staffRolesPermissions->role_name ?? 'Unknown Role'; // Adjust based on your relationships
//
//        // Create a notification for the admin
//        $adminUserId = 1; // Replace with logic to dynamically fetch the admin user(s)
//        Notification::create([
//            'admin_user_id' => $adminUserId,
//            'report_id' => $report->id,
//            'title' => 'Report Status Updated',
//            'message' => "Report '{$report->defect}' at '{$report->location}' was updated from '{$previousStatus}' to '{$report->status}' by staff: {$staffName} ({$staffRole}).",
//            'is_read' => false,
//        ]);
//
//        // Redirect back with success message
//        return redirect()->route('Staff.manage-tagging')
//            ->with('message', 'Report status updated successfully!');
//    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'newStatus' => 'required|string',
            'reportId' => 'required|exists:reports,id',
        ]);

        $report = Report::findOrFail($request->input('reportId'));
        $previousStatus = $report->status;

        $report->status = $request->input('newStatus');
        $report->save();

        // Identify the staff who performed the update
        $staff = Staff::where('user_id', auth()->id())->with('staffRolesPermissions.staffRole')->first();

        $staffFullName = optional($staff->user)->first_name . ' ' . optional($staff->user)->last_name ?? 'Unknown Staff';

        // Get the staff role name
        $staffRole = optional($staff->staffRolesPermissions->staffRole)->name ?? 'Unknown Role';


        // Notify the first admin (type = 1)
        $admin = User::where('user_type', 1)->first();  // Assuming type 1 = Admin

        if ($admin) {
            Notification::create([
                'notifiable_id' => $admin->id,
                'notifiable_type' => User::class,
                'report_id' => $report->id,
                'title' => 'Report Status Updated',
                'message' => "The report '{$report->defect}' has been updated from '{$previousStatus}' to '{$report->status}' by Staff {$staffFullName} ({$staffRole}).",
                'is_read' => false,
            ]);
        }

        return redirect()->route('Staff.manage-tagging')
            ->with('message', 'Report status updated successfully!');
    }


//    public function submitReport(Request $request)
//    {
////        dd($request);
//        // Save the data to the database
//        // Extract the base64 data from the photo string
//        $photoData = $request->photo;
//
//        // Remove the base64 prefix (data:image/png;base64,)
//        $photoData = str_replace('data:image/png;base64,', '', $photoData);
//
//        // Decode the base64 data
//        $image = base64_decode($photoData);
//
//        // Generate a unique file name for the image
//        $imageName = 'report_photo_' . time() . '.png';
//        $formattedDate = Carbon::createFromFormat('F d, Y', $request->date)->format('Y-m-d');
//        $formattedTime = Carbon::parse($request->time)->format('H:i:s');
//        // Save the image to storage (e.g., storage/app/public/reports)
//        $imagePath = Storage::disk('public')->put('reports/' . $imageName, $image);
//        $path = "reports/".$imageName;  // This will return the full URL or relative path to the image
//
//        $annotatedPath = 'reports/report_photo_' . time() . '_annotated.jpg';
//        Report::create([
//            'reporter_id' => Auth::id(),
//            'defect' => "Pothole",
//            'lat' => $request->latitude,
//            'lng' => $request->longitude,
//            'location' => $request->address,
//            'date' => $formattedDate,  // Store the formatted date
//            'time' => $formattedTime,
//            'severity' => 1,
//            'image' => $path,
//            'image_annotated' => $annotatedPath,
//            'status' => "Unfixed"
//
//
//        ]);
//
//        return redirect()->route('report-road-issue')->with('success', true);
//        // Send a success message back to the frontend
////        $this->dispatchBrowserEvent('report-saved', ['message' => 'Report submitted successfully!']);
//    }

//    public function submitReport(Request $request)
//    {
//        // Decode base64 photo
//        $photoData = str_replace('data:image/png;base64,', '', $request->photo);
//        $image = base64_decode($photoData);
//
//        // Generate filenames
//        $timestamp = time();
//        $imageName = "report_photo_{$timestamp}.png";
//        $annotatedPath = "output/reports/report_photo_{$timestamp}_annotated.jpg";
//        $jsonPath = storage_path("app/public/output/reports/report_photo_{$timestamp}.json");
//
//        // Save image
//        $imagePath = Storage::disk('public')->put("reports/{$imageName}", $image);
//        $fullImagePath = "reports/{$imageName}";
//
//        // Wait for the JSON file to be generated
//        $issue_name = "Unknown";
//        $timeout = 10; // Max wait time in seconds
//        $startTime = time();
//
//        while (!file_exists($jsonPath) && (time() - $startTime) < $timeout) {
//            usleep(500000); // Wait 0.5 seconds before checking again
//        }
//
//        // If JSON exists, read and extract issue name
//        if (file_exists($jsonPath)) {
//            $jsonData = json_decode(file_get_contents($jsonPath), true);
//
//            if (!empty($jsonData['prediction'])) {
//                $highestPrediction = collect($jsonData['prediction'])
//                    ->sortByDesc(fn($p) => $p['best_probability'] * ($p['rect']['width'] * $p['rect']['height']))
//                    ->first();
//                $issue_name = $highestPrediction['name'] ?? 'Unknown';
//            }
//            else{
//                // ✅ Error feedback
//                session()->flash('feedback', 'No road defect found. Please try again.');
//                session()->flash('feedback_type', 'error');
//
//                return redirect()->route('report-road-issue');
////                return redirect()->route('report-road-issue')->with('error', 'No road issues found, please retry!.');
//            }
//        }
//
//        // Check if report already exists
//        $existingReport = Report::where([
//            ['lat', '=', $request->latitude],
//            ['lng', '=', $request->longitude],
//            ['street', '=', $request->street],
//            ['barangay', '=', $request->barangay],
//            ['defect', '=', $issue_name]
//        ])->first();
//
//        if ($existingReport) {
//            // Save to suggestions table instead
//            Suggestion::create([
//                'report_id' => $existingReport->id,
//                'reporter_id' => Auth::id(),
//                'is_match' => false, // Default to false until user confirms
//                'response_deadline' => Carbon::now()->addDays(5), // Set deadline 5 days later
//                'defect' => $issue_name,
//                'lat' => $request->latitude,
//                'lng' => $request->longitude,
//                'location' => $request->address,
//                'street' => $request->street,
//                'purok' => $request->purok,
//                'barangay' => $request->barangay,
//                'date' => Carbon::createFromFormat('F d, Y', $request->date)->format('Y-m-d'),
//                'time' => Carbon::parse($request->time)->format('H:i:s'),
//                'severity' => 1,
//                'image' => $fullImagePath,
//                'image_annotated' => $annotatedPath,
//                'status' => "Unfixed"
//            ]);
//            return redirect()->route('report-road-issue')->with('success', 'Your report was marked as existing, please check Suggestions.');
//        }

        // No existing report found, save as new report
//        $report = Report::create([
//            'reporter_id' => Auth::id(),
//            'defect' => $issue_name,
//            'lat' => $request->latitude,
//            'lng' => $request->longitude,
//            'location' => $request->address,
//            'street' => $request->street,
//            'purok' => $request->purok,
//            'barangay' => $request->barangay,
//            'date' => Carbon::createFromFormat('F d, Y', $request->date)->format('Y-m-d'),
//            'time' => Carbon::parse($request->time)->format('H:i:s'),
//            'severity' => 1,
//            'image' => $fullImagePath,
//            'image_annotated' => $annotatedPath,
//            'status' => "Unfixed"
//        ]);
//        $reporter = Auth::user();
//
//        // ✅ Fetch Admins and Staff
//        $admins = User::where('user_type', 1)->get();
//        $staff = Staff::with('user')->get();
//
//        if ($admins->isEmpty() && $staff->isEmpty()) {
//            throw new \Exception('No admins or staff found.');
//        }
//
//        // ✅ Admin & Staff Notification
//        $notificationData = [
//            'report_id' => $report->id,
//            'title' => 'Report Created',
//            'message' => "A new report has been submitted by {$reporter->name} at {$request->address}.",
//            'is_read' => false,
//        ];
//
//        // ✅ Notify Admins
//        $this->notifyUsers($admins, $notificationData, User::class);
//
//        // ✅ Notify Staff only if the reporter is NOT a staff member
//        if ($reporter->user_type !== 3) {
//            $this->notifyUsers($staff, $notificationData, Staff::class);
//        }
//
//        // ✅ Reporter Notification - Corrected Message
//        Notification::create([
//            'report_id' => $report->id,
//            'title' => 'Report Submitted',
//            'message' => "You submitted a new road issue successfully at {$request->address}.",
//            'notifiable_id' => $reporter->id,
//            'notifiable_type' => User::class,
//            'is_read' => false,
//        ]);
//
//        // ✅ Success feedback
//        session()->flash('feedback', 'Report submitted successfully!');
//        session()->flash('feedback_type', 'success');
//
//        return redirect()->route('report-road-issue')->with('success', true);
//        // Send a success message back to the frontend
////        $this->dispatchBrowserEvent('report-saved', ['message' => 'Report submitted successfully!']);
//    }
    public function TempSubmitReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude'  => 'required',
            'longitude' => 'required',
            'address'   => 'required',
            'date'      => 'required|date',
            'time'      => 'required',
            'photo'     => 'required',
        ]);

        if ($validator->fails()) {
            // Flash error session so your animation triggers
            session()->flash('feedback', 'Please wait for the details to load.');
            session()->flash('feedback_type', 'error');

            // Optionally redirect back with validation messages too
            return redirect()->back()->withErrors($validator)->withInput();
        }
        // Normalize address and check for exact 'Tagum' word
        $address = strtolower(trim($request->address));
        if (!preg_match('/\btagum\b/', $address)) {
            return redirect()->back()->with('not_from_tagum', true);
        }
        // Decode base64 photo
        $photoData = str_replace('data:image/png;base64,', '', $request->photo);
        $image = base64_decode($photoData);

        if ($image === false || @getimagesizefromstring($image) === false) {
            return redirect()->back()->with('no_defect_modal_open', true);
        }

        // Generate filenames
        $timestamp = time();
        $imageName = "report_photo_{$timestamp}.png";
        $annotatedPath = "output/reports/report_photo_{$timestamp}_annotated.jpg";
        $jsonPath = storage_path("app/public/output/reports/report_photo_{$timestamp}.json");

        // Save image
        $imagePath = Storage::disk('public')->put("reports/{$imageName}", $image);
        $fullImagePath = "output/reports/{$imageName}";

        // Wait for the JSON file to be generated
        $issue_name = "Unknown";
        $timeout = 5; // Max wait time in seconds
        $startTime = time();

        while (!file_exists($jsonPath) && (time() - $startTime) < $timeout) {
            usleep(500000); // Wait 0.5 seconds before checking again
        }

        // If JSON exists, read and extract issue name
        if (file_exists($jsonPath)) {
            $jsonData = json_decode(file_get_contents($jsonPath), true);

            if (!empty($jsonData['prediction'])) {
                $largest = collect($jsonData['prediction'])
                    ->sortByDesc(fn($p) => $p['rect']['width'] * $p['rect']['height'])
                    ->first();

                $issue_name = $largest ? ucfirst($largest['name']) : 'Unknown';
            } else {
                return redirect()->back()->with('no_defect_modal_open', true);
            }
        }else{
            return redirect()->back()->with('no_defect_modal_open', true);
        }


        // 🔍 Check if `street` and `barangay` exist, otherwise extract from `address`
        $street = $request->street;
        $barangay = $request->barangay;

        if (!$street || !$barangay) {
            $fullAddress = strtolower($request->address);

            // Get all stored streets and barangays from the database
            $storedStreets = \DB::table('streets')->pluck('name')->toArray();
            $storedBarangays = \DB::table('barangays')->pluck('name')->toArray();

            // Search for a matching street in the full address
            foreach ($storedStreets as $savedStreet) {
                if (str_contains($fullAddress, strtolower($savedStreet))) {
                    $street = $savedStreet;
                    break;
                }
            }

            // Search for a matching barangay in the full address
            foreach ($storedBarangays as $savedBarangay) {
                if (str_contains($fullAddress, strtolower($savedBarangay))) {
                    $barangay = $savedBarangay;
                    break;
                }
            }
        }

        // Ensure they are not null
        $street = $street ?? 'Unknown Street';
        $barangay = $barangay ?? 'Unknown Barangay';


        // Find existing report or create new one
        $report = TemporaryReport::where('reporter_id', Auth::id())->first();

        $data = [
            'defect' => $issue_name,
            'lat' => $request->latitude,
            'lng' => $request->longitude,
            'location' => $request->address,
            'street' => $street,
            'purok' => $request->purok ?? 'Unknown Purok',
            'barangay' => $barangay,
            'date' => Carbon::createFromFormat('F d, Y', $request->date)->format('Y-m-d'),
            'time' => Carbon::parse($request->time)->format('H:i:s'),
            'severity' => 5,
            'label' => 5,
            'image' => $fullImagePath,
            'image_annotated' => $annotatedPath,
            'status' => "Unfixed"
        ];

        if ($report) {
            // Update existing report
            $report->update($data);
        } else {
            // Create new report
            $report = TemporaryReport::create(array_merge(['reporter_id' => Auth::id()], $data));
        }

        return redirect()->route('report-road-issue')->with('success', true);
    }


    public function TempUpdate(Request $request)
    {
        // Decode base64 photo
        $photoData = str_replace('data:image/png;base64,', '', $request->photo);
        $image = base64_decode($photoData);

        // Generate filenames
        $timestamp = time();
        $imageName = "update_photo_{$timestamp}.png";
        $annotatedPath = "update/update_photo_{$timestamp}_annotated.jpg";
        $jsonPath = storage_path("app/public/output/reports/report_photo_{$timestamp}.json");

        // Save image
         Storage::disk('public')->put("temporary/{$imageName}", $image);
        $fullImagePath = "updates/{$imageName}";
        // Wait for the JSON file to be
        $timeout = 10; // Max wait time in seconds
        $startTime = time();

        while (!file_exists($jsonPath) && (time() - $startTime) < $timeout) {
            usleep(500000); // Wait 0.5 seconds before checking again
        }

        // If JSON exists, read and extract issue name
        if (file_exists($jsonPath)) {
            $jsonData = json_decode(file_get_contents($jsonPath), true);

            if (!empty($jsonData['prediction'])) {
                return redirect()->back()->with('dont_allow_update_open', true);
            } else {

            }
        }else{
            return redirect()->back()->with('no_defect_modal_open', true);
        }

//        return redirect()->back()->with('no_defect_modal_open', true);

        $report = TemporaryUpdate::where('reporter_id', Auth::id())->first();

        $data = [
            'reporter_id' => Auth::id(),
            'date' => Carbon::createFromFormat('F d, Y', $request->date)->format('Y-m-d'),
            'time' => Carbon::parse($request->time)->format('H:i:s'),
            'image' => $fullImagePath,
            'lat' => $request->latitude,
            'lng' => $request->longitude,
        ];

        if ($report) {
            // Update existing report
            $report->update($data);
        } else {
            // Create new report
            $report = TemporaryUpdate::create(array_merge(['reporter_id' => Auth::id()], $data));
        }

        return redirect()->back()->with('success', true);
    }

    public function manageTagging()
    {
        return view('iroadcheck.prototype.Staff.manage-tagging');
    }

    public function captureRoadDefect()
    {
        return view('iroadcheck.prototype.Staff.update-road-issue');
    }


}
