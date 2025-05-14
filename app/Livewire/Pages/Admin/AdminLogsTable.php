<?php

namespace App\Livewire\Pages\Admin;

use App\Exports\AdminLogsExport;
use App\Models\AdminLog;
use App\Models\User; // Add the Staff model (assuming this is the model for admins)
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

class AdminLogsTable extends Component
{
    use WithoutUrlPagination, WithPagination;

    // Search term
    public string $search = '';

    // Date Filter
    public string $date_range_filter = '';

    // Rows per page
    public int $rowsPerPage = 10;

    // Sorting
    public string $sort_by = 'log_id'; // Default sorting by primary key
    public string $sort_direction = 'desc'; // Default sorting direction

    /**
     * Export the filtered admin logs to an Excel file.
     */
    public function exportAdminLogs(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $filteredAdminLogs = $this->getFilteredQuery()->get();

        return Excel::download(new AdminLogsExport($filteredAdminLogs), 'admin_logs_report.xlsx');
    }

    /**
     * Build the filtered query for admin logs.
     */
    public function getFilteredQuery(): Builder
    {
        $admin_logs_query = AdminLog::with(['admin'])->select('admin_logs.*');

        // Apply Date Range Filter
        if (!empty($this->date_range_filter)) {
            $date_range = explode(' to ', $this->date_range_filter);

            foreach ($date_range as $key => $date) {
                $date_range[$key] = date('Y-m-d', strtotime($date));
            }

            if (count($date_range) === 2) {
                $admin_logs_query->whereBetween('created_at', [
                    Carbon::parse($date_range[0])->startOfDay(),
                    Carbon::parse($date_range[1])->endOfDay(),
                ]);
            }
        }

        // Apply Search Filter
        if ($this->search) {
            $search = '%' . $this->search . '%';
            $admin_logs_query->where(function ($query) use ($search) {
                $query->where('action', 'like', $search)
                    ->orWhere('dateTime', 'like', $search)
                    ->orWhereHas('admin', function ($admin_query) use ($search) {
                        $admin_query->where('first_name', 'like', $search)
                            ->orWhere('last_name', 'like', $search);
                    });
            });
        }

        // Apply Sorting
        if ($this->sort_by) {
            if ($this->sort_by === 'admin.first_name') {
                // Sorting by admin first name
                $admin_logs_query
                    ->leftJoin('admins', 'admin_logs.admin_id', '=', 'admins.id')  // Use LEFT JOIN
                    ->leftJoin('users', 'admins.user_id', '=', 'users.id')  // Use LEFT JOIN
                    ->orderBy('users.first_name', $this->sort_direction);
            } elseif ($this->sort_by === 'admin.last_name') {
                // Sorting by admin last name
                $admin_logs_query
                    ->leftJoin('admins', 'admin_logs.admin_id', '=', 'admins.id')  // Use LEFT JOIN
                    ->leftJoin('users', 'admins.user_id', '=', 'users.id')  // Use LEFT JOIN
                    ->orderBy('users.last_name', $this->sort_direction);
            } elseif ($this->sort_by === 'dateTime') {
                // Sorting by dateTime column in admin_logs table
                $admin_logs_query->orderBy('admin_logs.dateTime', $this->sort_direction);
            } else {
                // Sorting by direct fields in admin_logs
                $admin_logs_query->orderBy($this->sort_by, $this->sort_direction);
            }
        }



        return $admin_logs_query;
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
    public function getDecryptedAdminLogs()
    {
        return $this->getFilteredQuery()->get()->map(function ($log) {
            if ($log->admin && $log->admin->user) {
                $log->admin->user->first_name = Crypt::decryptString($log->admin->user->first_name);
                $log->admin->user->last_name = Crypt::decryptString($log->admin->user->last_name);
            }
            return $log;
        });
    }

    /**
     * Render the Livewire component.
     */
    public function render(): Factory|Application|View|\Illuminate\View\View
    {
        $adminLogs = $this->getFilteredQuery()
            ->paginate($this->rowsPerPage);

        session()->forget('hideSearchBar');
        return view('livewire.pages.admin.admin-logs-table', [
            'adminLogs' => $adminLogs,
            'admin_logs' => $this->getDecryptedAdminLogs(),
        ]);
    }
}
