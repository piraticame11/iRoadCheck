<?php

namespace App\Livewire\Pages\Staff;

use App\Exports\RoadDefectReportsExport;
use App\Models\Report;
use App\Models\Severity;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class RoadDefectReports extends Component
{
    use WithoutUrlPagination, WithPagination;

    public bool $hideSearchbar = true;
    public string $table_search = '';
    public int $rowsPerPage = 10;
    public string $sort_by = 'id';
    public string $sort_direction = 'asc';

    public string $statusFilter = '';
    public string $severityFilter = '';
    public string $barangayFilter = '';
    public string $selectedDefect = '';
    public string $locationFilter = '';
    public ?string $start_date = null;
    public ?string $end_date = null;

    public array $defectTypes = [];
    public array $statuses = [];
    public array $barangays = [];
    public array $severities = [];
    public array $locations = [];
    public $status;


    public function mount(Request $request): void
    {
        $this->status = $request->query('status'); // 'Unfixed' if coming from the card

        $reportData = Cache::remember('report_dropdown_data', 3600, function () {
            return Report::select('defect', 'status', 'barangay', 'street', 'purok')->get();
        });

        session(['hideSearchbar' => true]);
        $this->defectTypes = $reportData->pluck('defect')->unique()->values()->toArray();
        $this->statuses = $reportData->pluck('status')->unique()->values()->toArray();
        $this->barangays = $reportData->pluck('barangay')->unique()->values()->toArray();
        $this->locations = $reportData->pluck('street')->merge($reportData->pluck('purok'))->unique()->values()->toArray();

        $this->severities = Cache::remember('severities_list', 3600, function () {
            return Severity::pluck('label', 'id')->toArray();
        });
    }

    public function viewRoadDefectReports($reportId): void
    {
        $this->dispatch('show-view-road-defect-reports-modal', reportId: $reportId);
    }

    public function updating($property): void
    {
        if (in_array($property, [
            'table_search',
            'statusFilter',
            'severityFilter',
            'barangayFilter',
            'selectedDefect',
            'locationFilter',
            'start_date',
            'end_date'
        ])) {
            $this->resetPage();
        }
    }

    public function toggleSorting(string $field): void
    {
        if ($this->sort_by === $field) {
            $this->sort_direction = $this->sort_direction === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sort_by = $field;
            $this->sort_direction = 'asc';
        }

        $this->resetPage();
    }

    public function resetFilterAndSearch(): void
    {
        $this->table_search = '';
        $this->statusFilter = '';
        $this->severityFilter = '';
        $this->barangayFilter = '';
        $this->selectedDefect = '';
        $this->locationFilter = '';
        $this->start_date = '';
        $this->end_date = '';
        $this->sort_by = 'id';
        $this->sort_direction = 'asc';
        $this->resetPage();
    }

    public function getFilteredQuery()
    {
        $query = Report::query()->with('severity');

        if ($this->table_search) {
            $query->where(function ($q) {
                $q->where('id', 'like', "%{$this->table_search}%")
                    ->orWhere('defect', 'like', "%{$this->table_search}%")
                    ->orWhere('location', 'like', "%{$this->table_search}%")
                    ->orWhere('street', 'like', "%{$this->table_search}%")
                    ->orWhere('purok', 'like', "%{$this->table_search}%")
                    ->orWhere('barangay', 'like', "%{$this->table_search}%")
                    ->orWhere('date', 'like', "%{$this->table_search}%")
                    ->orWhereHas('severity', fn($subQuery) =>
                    $subQuery->where('label', 'like', "%{$this->table_search}%")
                    )
                    ->orWhere('status', 'like', "%{$this->table_search}%");
            });
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->severityFilter) {
            $query->whereHas('severity', fn($q) =>
            $q->where('label', $this->severityFilter)
            );
        }

        if ($this->barangayFilter) {
            $query->where('barangay', $this->barangayFilter);
        }

        if ($this->selectedDefect) {
            $query->where('defect', $this->selectedDefect);
        }

        if ($this->locationFilter) {
            $query->whereRaw("CONCAT(street, ' ', purok) LIKE ?", ["%{$this->locationFilter}%"]);
        }

        if ($this->start_date && $this->end_date) {
            $query->whereBetween('date', [$this->start_date, $this->end_date]);
        } elseif ($this->start_date) {
            $query->whereDate('date', '>=', $this->start_date);
        } elseif ($this->end_date) {
            $query->whereDate('date', '<=', $this->end_date);
        }

        if ($this->sort_by === 'severity.label') {
            $query->join('severities', 'reports.severity_id', '=', 'severities.id')
                ->select('reports.*', 'severities.label as severity_label')
                ->orderBy('severities.label', $this->sort_direction);
        } else {
            $query->orderBy($this->sort_by, $this->sort_direction);
        }

        return $query;
    }

    public function exportRoadDefectReports(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $filteredRoadDefectReports = $this->getFilteredQuery()->get();

        return Excel::download(new RoadDefectReportsExport($filteredRoadDefectReports), 'road_defect_reports.xlsx');
    }

    public function render(): Factory|View|Application|\Illuminate\View\View
    {
        session(['hideSearchbar' => true]);

        return view('livewire.pages.staff.road-defect-reports', [
            'roadDefectReports' => $this->getFilteredQuery()->paginate($this->rowsPerPage),
        ]);
    }
}
