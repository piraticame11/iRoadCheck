<?php

namespace App\Livewire\Admin;

use App\Models\Notification;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;
use App\Models\Report;
use App\Models\Staff;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;


class NotificationDropdown extends Component
{
    public Collection|array $notifications = [];
    public string $notifications_count = '0';

    public function getListeners(): array
    {
        return [
            'echo-presence:library-session,.staff-report-updated' => 'onStaffReportUpdated',
        ];
    }

    public function mount(): void
    {
        $this->fetchNotifications();
    }

    public function fetchNotifications(): void
    {
        $this->notifications = Notification::with(['report']) // Removed 'staff' since no `notifiable_type`
        ->where('notifiable_id', auth()->id())
            ->where('is_read', false)
            ->orderByDesc('created_at')
            ->get();

        $this->notifications_count = $this->getStringedNotificationCount();
    }

    public function onStaffReportUpdated(): void
    {
        $this->fetchNotifications();

        $this->dispatch('staff_report_updated');
    }

    public function viewNotification(Notification $notification): Redirector
    {
        $parsed_notification = [
            'title' => $notification->title,
            'message' => $notification->message,
            'report_details' => [
                'defect' => $notification->report->defect ?? 'N/A',
                'location' => $notification->report->location ?? 'N/A',
                'status' => $notification->report->status ?? 'N/A',
            ],
            'created_at' => $notification->created_at->diffForHumans(),
        ];

        $this->dispatch('show-view-notification-modal', ['notification' => $parsed_notification]);

        $notification->update(['is_read' => true]);

        $this->fetchNotifications();

        // Correct redirect to report details page
        return redirect()->route('admin.road-defect-reports', ['report_id' => $notification->report_id]);
    }

    private function getStringedNotificationCount(): string
    {
        $count = $this->notifications->count();
        return $count > 99 ? '99+' : (string)$count;
    }

    public function markAsRead($notificationId): void
    {
        $notification = Notification::find($notificationId);

        if ($notification && !$notification->is_read) {
            $notification->update(['is_read' => true]);
        }

        $this->fetchNotifications();
    }

    public function render(): Factory|View|Application|\Illuminate\View\View
    {
        return view('livewire.admin.notification-dropdown');
    }
}
