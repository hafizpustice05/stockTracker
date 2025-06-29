<?php

namespace App\Services;

use App\Events\RequisitionCreated;
use App\Models\Requisition;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RequisitionService
{
    /**
     * Create a new requisition for the given employee.
     *
     * @param User $employee
     * @param array $payload
     * @return Requisition
     */
    public function create(User $employee, array $payload): Requisition
    {
        return DB::transaction(function () use ($employee, $payload) {
            $req = $employee->requisitions()->create();

            foreach ($payload['items'] as $item) {
                $req->items()->attach($item['id'], ['qty' => $item['qty']]);
            }

            // notify all admins via mail 
            event(new RequisitionCreated($req));

            return $req->load('items');
        });
    }

    /**
     * Approve a pending requisition.
     *
     * @param Requisition $req
     * @param User $admin
     * @throws ValidationException
     */
    public function approve(Requisition $req, User $admin): void
    {
        if ($req->status !== 'pending') {
            throw ValidationException::withMessages(['status' => 'Already processed']);
        }

        $req->update([
            'status' => 'approved',
            'approved_by' => $admin->id,
            'approved_at' => now(),
        ]);
    }

    /**
     * Reject a pending requisition with a reason.
     *
     * @param Requisition $req
     * @param User $admin
     * @param string $reason
     * @throws ValidationException
     */
    public function reject(Requisition $req, User $admin, string $reason): void
    {
        if ($req->status !== 'pending') {
            throw ValidationException::withMessages(['status' => 'Already processed']);
        }

        $req->update([
            'status' => 'rejected',
            'rejected_by' => $admin->id,
            'rejection_reason' => $reason,
        ]);
    }
}
