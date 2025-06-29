<?php

namespace App\Listeners;

use App\Events\RequisitionCreated;
use App\Models\User;
use App\Notifications\AdminAlertNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyAdminsOfRequisition implements ShouldQueue
{
    public function handle(RequisitionCreated $event): void
    {
        $requisition = $event->requisition;

        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(
                new AdminAlertNotification(
                    title: 'New Requisition Submitted',
                    message: "A new Requisition  was submitted by {$requisition->creator->name}.",
                    url: url("/admin/requisitions/")
                )
            );
        }
    }
}
