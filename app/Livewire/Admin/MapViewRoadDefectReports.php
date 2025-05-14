<?php

namespace App\Livewire\Admin;

use App\Models\Report;
use App\Models\Severity;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\HtmlString;

class MapViewRoadDefectReports extends Component
{
    public array $reports = [];
    public array $geoJsonData = [];
    public array $barangays = [];
    public array $statuses = [];
    public array $defectTypes = [];
    public array $severities = [];

    protected $listeners = ['update-map-data' => 'updateMapData'];

    /**
     * Mount the component and load initial data.
     */
    public function mount(): void
    {
        $this->reports = Report::with(['severity', 'reporter:id,first_name,last_name']) // Make sure this relation exists
        ->select('*')
            ->get()
            ->map(function ($report) {
                $report->formatted_date = $report->date
                    ? Carbon::parse($report->date)->format('F j, Y')
                    : null;

                $report->days_ago = $report->date
                    ? (int) Carbon::parse($report->date)->diffInDays(now()) . ' days ago'
                    : null;

                $report->severity_label = $report->severity ?? 'Unknown';

                // Optional: Format full name for easier frontend use
                $report->reporter_full_name = $report->reporter
                    ? $report->reporter->first_name . ' ' . $report->reporter->last_name
                    : 'Unknown';

                return $report;
            })
            ->toArray();




        // ✅ Load unique filtering options
        $this->barangays = Report::pluck('barangay')->unique()->filter()->values()->toArray();
        $this->statuses = Report::pluck('status')->unique()->filter()->values()->toArray();
        $this->defectTypes = Report::pluck('defect')->unique()->filter()->values()->toArray();

        // Get readable severity labels
        $this->severities = Severity::pluck('label')->unique()->filter()->values()->toArray();

        // ✅ Load GeoJSON data
        $path = public_path('geoJSON/tagumCityRoad.json');
        if (File::exists($path)) {
            $data = File::get($path);
            $this->geoJsonData = json_decode($data, true) ?? [];
        }
    }

    /**
     * Update the reports data dynamically when a filter is applied.
     */
    public function updateMapData(array $data): void
    {
        $this->reports = $data['filteredData'] ?? [];
        $this->dispatch('updateMap', ['filteredData' => $this->reports]);
    }

    /**
     * Render the Livewire component view.
     */
    public function render(): Factory|Application|View|\Illuminate\View\View
    {
        return view('livewire.admin.map-view-road-defect-reports', [
            'reports' => $this->reports,
            'geoJsonData' => $this->geoJsonData,
            'barangays' => $this->barangays,
            'statuses' => $this->statuses,
            'defectTypes' => $this->defectTypes,
            'severities' => $this->severities,
        ]);
    }
}
