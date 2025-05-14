<?php

namespace App\Livewire\Pages\Admin;

use App\Exports\SystemLogsExport;
use App\Models\SystemLog;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\Builder;

class SystemLogsTable extends Component
{
    use WithPagination;

    // Search term
    public string $search = '';

    // Date Filter
    public string $date_range_filter = '';

    // Rows per page
    public int $rowsPerPage = 10;

    // Sorting
    public string $sort_by = 'id'; // Default sorting by primary key
    public string $sort_direction = 'desc'; // Default sorting direction

    /**
     * Export the filtered system logs to an Excel file.
     */
//    public function exportSystemLogs(): \Symfony\Component\HttpFoundation\BinaryFileResponse
//    {
//        $filteredSystemLogs = $this->getFilteredQuery()->get();
//
//        return Excel::download(new SystemLogsExport($filteredSystemLogs), 'system_logs_report.xlsx');
//    }

    /**
     * Build the filtered query for system logs.
     */
    public function getFilteredQuery(): Builder
    {
        $system_logs_query = SystemLog::select('system_logs.*');

        // Apply Date Range Filter
        if (!empty($this->date_range_filter)) {
            $date_range = explode(' to ', $this->date_range_filter);

            foreach ($date_range as $key => $date) {
                $date_range[$key] = date('Y-m-d', strtotime($date));
            }

            if (count($date_range) === 2) {
                $system_logs_query->whereBetween('created_at', [
                    Carbon::parse($date_range[0])->startOfDay(),
                    Carbon::parse($date_range[1])->endOfDay(),
                ]);
            }
        }

        // Apply Search Filter
        if ($this->search) {
            $search = '%' . $this->search . '%';
            $system_logs_query->where(function ($query) use ($search) {
                $query->where('transaction_id', 'like', $search)
                    ->orWhere('action', 'like', $search)
                    ->orWhere('created_at', 'like', $search);
            });
        }

        // Apply Sorting
        $system_logs_query->orderBy($this->sort_by, $this->sort_direction);

        return $system_logs_query;
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

    /**
     * Render the Livewire component.
     */
    public function render(): Factory|Application|View|\Illuminate\View\View
    {
        $systemLogs = $this->getFilteredQuery()
            ->paginate($this->rowsPerPage);

        session()->forget('hideSearchBar');
        return view('livewire.pages.admin.system-logs-table', [
            'systemLogs' => $systemLogs,
        ]);
    }
}
