<?php

namespace App\Livewire\Pages\Admin;

use App\Exports\ResidentLogsExport;
use App\Models\ResidentLog;
use App\Models\User; // Assuming Staff model for resident
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Crypt;

class ResidentLogsTable extends Component
{
    use WithoutUrlPagination, WithPagination;

    public string $search = '';  // Search term
    public string $date_range_filter = '';  // Date filter
    public int $rowsPerPage = 10;  // Rows per page
    public string $sort_by = 'log_id';  // Default sorting by primary key
    public string $sort_direction = 'desc';  // Default sorting direction

    /**
     * Export the filtered resident logs to an Excel file.
     */
    public function exportResidentLogs(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $filteredResidentLogs = $this->getFilteredQuery()->get();
        return Excel::download(new ResidentLogsExport($filteredResidentLogs), 'resident_logs_report.xlsx');
    }

    /**
     * Build the filtered query for resident logs.
     */
    public function getFilteredQuery(): Builder
    {
        $resident_logs_query = ResidentLog::with(['resident'])->select('resident_logs.*');

        // Apply Date Range Filter
        if (!empty($this->date_range_filter)) {
            $date_range = explode(' to ', $this->date_range_filter);

            foreach ($date_range as $key => $date) {
                $date_range[$key] = date('Y-m-d', strtotime($date));
            }

            if (count($date_range) === 2) {
                $resident_logs_query->whereBetween('created_at', [
                    Carbon::parse($date_range[0])->startOfDay(),
                    Carbon::parse($date_range[1])->endOfDay(),
                ]);
            }
        }

        // Apply Search Filter
        if ($this->search) {
            $search = '%' . $this->search . '%';
            $resident_logs_query->where(function ($query) use ($search) {
                $query->where('action', 'like', $search)
                    ->orWhere('dateTime', 'like', $search)
                    ->orWhereHas('resident', function ($resident_query) use ($search) {
                        $resident_query->where('resident_id', 'like', $search) // Ensure 'resident_id' is the correct column
                        ->orWhere('first_name', 'like', $search)
                            ->orWhere('last_name', 'like', $search);
                    });
            });
        }

        // Apply Sorting
        if ($this->sort_by) {
            if ($this->sort_by === 'resident.first_name') {
                // Sorting by resident first name
                $resident_logs_query
                    ->leftJoin('residents', 'resident_logs.resident_id', '=', 'residents.id')  // Use LEFT JOIN to ensure all resident logs are included
                    ->leftJoin('users', 'residents.user_id', '=', 'users.id')  // Use LEFT JOIN for user info
                    ->orderBy('users.first_name', $this->sort_direction);  // Sorting by resident's first name
            } elseif ($this->sort_by === 'resident.last_name') {
                // Sorting by resident last name
                $resident_logs_query
                    ->leftJoin('residents', 'resident_logs.resident_id', '=', 'residents.id')  // Use LEFT JOIN to ensure all resident logs are included
                    ->leftJoin('users', 'residents.user_id', '=', 'users.id')  // Use LEFT JOIN for user info
                    ->orderBy('users.last_name', $this->sort_direction);  // Sorting by resident's last name
            } elseif ($this->sort_by === 'dateTime') {
                // Sorting by dateTime column in resident_logs table
                $resident_logs_query->orderBy('resident_logs.dateTime', $this->sort_direction);  // Sorting by resident logs dateTime
            } else {
                // Sorting by direct fields in resident_logs
                $resident_logs_query->orderBy($this->sort_by, $this->sort_direction);  // Sorting by other columns in the resident_logs table
            }
        }

        return $resident_logs_query;
    }

    /**
     * Toggle sorting by a column.
     */
    public function toggleSorting(string $field): void
    {
        if ($this->sort_by === $field) {
            $this->sort_direction = $this->sort_direction === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sort_by = $field;
            $this->sort_direction = 'asc';
        }
    }

    /**
     * Reset the search filter.
     */
    public function resetSearch(): void
    {
        $this->search = '';
        $this->date_range_filter = '';
        $this->resetPage();
    }

    /**
     * Reset all filters and search.
     */
    public function resetFiltersAndSearch(): void
    {
        $this->reset(['search', 'date_range_filter']);
        $this->resetPage();
    }

    /**
     * Reset pagination when rows per page are updated.
     */
    public function updatingRowsPerPage(): void
    {
        $this->resetPage();
    }

    public function getDecryptedResidentLogs()
    {
        return $this->getFilteredQuery()->get()->map(function ($log) {
            if ($log->resident && $log->resident->user) {
                $log->resident->user->first_name = Crypt::decryptString($log->resident->user->first_name);
                $log->resident->user->last_name = Crypt::decryptString($log->resident->user->last_name);
            }
            return $log;
        });
    }
    /**
     * Render the Livewire component.
     */
    public function render(): Factory|Application|View|\Illuminate\View\View
    {
        $residentLogs = $this->getFilteredQuery()->paginate($this->rowsPerPage);

        session()->forget('hideSearchBar');
        return view('livewire.pages.admin.resident-logs-table', [
            'residentLogs' => $residentLogs,
            'resident_logs' => $this->getDecryptedResidentLogs(),
        ]);
    }
}
