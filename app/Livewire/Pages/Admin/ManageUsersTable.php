<?php

namespace App\Livewire\Pages\Admin;

use App\Enums\Staff\StaffStatus;
use App\Livewire\Modals\Admin\UsersModal\EditUserAccountModal;
use App\Livewire\Modals\Admin\UsersModal\ViewUserAccountModal;
use App\Models\User;
use App\Models\Staff;
use App\Models\StaffRole;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use App\Exports\StaffsDataExport;
use Maatwebsite\Excel\Facades\Excel;

class ManageUsersTable extends Component
{
    use WithoutUrlPagination, WithPagination;

    // Search
    public $search = '';

    public int $rowsPerPage = 10;

    public Collection $user_statuses;
    public Collection $roles;

    // Staff account to view
    public ?Staff $staff_account_to_viewed = null;
    public ?Staff $staff_account_to_edited = null;

    // Filters
    public string $user_status_filter = '';
    public string $staff_roles_filter = '';

    // Sorting
    public string $sort_by = 'id';

    public string $sort_direction = 'asc'; // 'asc' or 'desc'

    // Listening for changes in pagination
    protected $updatesQueryString = ['rowsPerPage'];

    public function mount(): void
    {
        $this->user_statuses = collect([
            [
                'key' => StaffStatus::ACTIVE,
                'label' => 'Active',
            ],
            [
                'key' => StaffStatus::INACTIVE,
                'label' => 'Inactive',
            ],
        ]);

        $this->roles = StaffRole::query()->get();
    }

    public function editUserAccount($staffId): void
    {
        $staff = Staff::findOrFail($staffId);
        $this->dispatch('show-edit-user-account-modal', [
            'staff' => $staff->id
        ])->to(EditUserAccountModal::class);
    }

    public function viewUserAccount($staffId): void
    {
        $staff = Staff::findOrFail($staffId);
        $this->dispatch('show-view-user-account-modal', staff: $staff);
    }

    public function toggleSorting($field): void
    {
        // If the field is the same as the current sort field, toggle the direction
        if ($this->sort_by === $field) {
            $this->sort_direction = $this->sort_direction === 'asc' ? 'desc' : 'asc';
        } else {
            // If it's a new field, set it as the sort field and default to ascending
            $this->sort_by = $field;
            $this->sort_direction = 'asc';
        }
    }

    public function resetFilterAndSearch(): void
    {
        $this->search = '';
        $this->user_status_filter = '';
        $this->staff_roles_filter = '';
        $this->sort_by = 'id';
    }

    public function updatedRowsPerPage(): void
    {
        $this->resetPage();
    }

    public function updatingRowsPerPage(): void
    {
        // Reset pagination when rows per page is updated
        $this->resetPage();
    }

    public function getFilteredQuery()
{
    $query = Staff::query()
        ->select('staffs.*')
        ->leftJoin('users', 'staffs.user_id', '=', 'users.id')
        ->leftJoin('staff_roles', 'staffs.staff_role', '=', 'staff_roles.id')
        ->with(['user', 'staffRole'])
        ->when($this->user_status_filter, function ($query) {
            $query->where('staffs.status', $this->user_status_filter);
        })
        ->when($this->staff_roles_filter, function ($query) {
            $query->where('staffs.staff_role', $this->staff_roles_filter);
        });


    // Apply sorting but handle user fields separately
    if (in_array($this->sort_by, ['first_name', 'last_name', 'middle_name', 'username'])) {
        // We'll handle this after decryption
    } elseif ($this->sort_by === 'staff_role') {
        $query->orderBy('staff_roles.name', $this->sort_direction);
    } else {
        $query->orderBy('staffs.' . $this->sort_by, $this->sort_direction);
    }
    return $query;
}

public function exportStaffs()
{
    try {
        $filters = [
            'status' => $this->user_status_filter,
            'staff_role_id' => $this->staff_roles_filter,
            'search' => $this->search,
        ];

        // Get the base query without search filtering
        $staffs = $this->getFilteredQuery()->get();

        // Decrypt user data
        foreach ($staffs as $staff) {
            if ($staff->user) {
                $staff->user->first_name = Crypt::decryptString($staff->user->first_name);
                $staff->user->middle_name = $staff->user->middle_name
                    ? Crypt::decryptString($staff->user->middle_name)
                    : null;
                $staff->user->last_name = Crypt::decryptString($staff->user->last_name);
                $staff->user->username = Crypt::decryptString($staff->user->username);
            }
        }

        // Filter by search term if needed
        if ($this->search) {
            $search = strtolower($this->search);
            $staffs = $staffs->filter(function ($staff) use ($search) {
                return
                    str_contains(strtolower($staff->user->first_name ?? ''), $search) ||
                    str_contains(strtolower($staff->user->middle_name ?? ''), $search) ||
                    str_contains(strtolower($staff->user->last_name ?? ''), $search) ||
                    str_contains(strtolower($staff->user->username ?? ''), $search) ||
                    ($staff->staffRolesPermissions &&
                     $staff->staffRolesPermissions->staffRole &&
                     str_contains(strtolower($staff->staffRolesPermissions->staffRole->name ?? ''), $search));
            });
        }

        return Excel::download(
            new StaffsDataExport($staffs, $filters),
            'staffs_report_' . now()->format('Y-m-d_His') . '.xlsx'
        );
    } catch (\Exception $e) {
        Log::error('Staff export failed: ' . $e->getMessage());
        $this->dispatch('notification', [
            'type' => 'error',
            'title' => 'Export Failed',
            'message' => 'Failed to export staff data. Please try again.'
        ]);
    }
}

    public function render(): Factory|View|Application
{
    $allowedSortFields = ['id', 'first_name', 'last_name', 'status', 'username', 'staff_role'];
    if (!in_array($this->sort_by, $allowedSortFields)) {
        $this->sort_by = 'id'; // Default to a valid column
    }

    // Get the base query (without search filtering)
    $query = $this->getFilteredQuery();

    // Execute the query to get the initial results
    $staffs = $query->get();

    // Decrypt user data
    foreach ($staffs as $staff) {
        if ($staff->user) {
            $staff->user->first_name = Crypt::decryptString($staff->user->first_name);
            $staff->user->middle_name = $staff->user->middle_name
                ? Crypt::decryptString($staff->user->middle_name)
                : null;
            $staff->user->last_name = Crypt::decryptString($staff->user->last_name);
            $staff->user->username = Crypt::decryptString($staff->user->username);
        }
    }


    // Now filter the decrypted data by search term if needed
    if ($this->search) {
        $search = strtolower($this->search);
        $staffs = $staffs->filter(function ($staff) use ($search) {
            // Check if any of the decrypted fields contain the search term
            return
                str_contains(strtolower($staff->user->first_name ?? ''), $search) ||
                str_contains(strtolower($staff->user->middle_name ?? ''), $search) ||
                str_contains(strtolower($staff->user->last_name ?? ''), $search) ||
                str_contains(strtolower($staff->user->username ?? ''), $search) ||
                ($staff->staffRole &&
                 str_contains(strtolower($staff->staffRolesPermissions->staffRole->name ?? ''), $search));
        });
    }

    // Sort the collection if sorting by a user field
    if (in_array($this->sort_by, ['first_name', 'last_name', 'middle_name', 'username'])) {
        $staffs = $staffs->sortBy([
            [$this->sort_by, $this->sort_direction === 'asc' ? 'asc' : 'desc']
        ]);
    }

    // Paginate the filtered collection
    $staffs = $this->paginateCollection($staffs, $this->rowsPerPage);

    session()->forget('hideSearchBar');

    // Return the view
    return view('livewire.pages.admin.manage-users-table', compact('staffs'));
}

protected function paginateCollection($items, $perPage)
{
    // Get current page from query string or default to 1
    $page = request()->input('page', 1);
    
    // Store the total count before slicing
    $totalCount = $items->count();
    
    // Slice the collection to get the items to display in current page
    $items = $items->slice(($page - 1) * $perPage, $perPage)->values();
    
    // Create our paginator and pass it to the view
    $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
        $items,
        $totalCount, // Use the total count before slicing
        $perPage,
        $page,
        ['path' => request()->url(), 'query' => request()->query()]
    );
    
    return $paginated;
}
}
