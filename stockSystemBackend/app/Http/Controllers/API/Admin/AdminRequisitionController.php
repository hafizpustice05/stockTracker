<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Requisition;
use App\Services\RequisitionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminRequisitionController extends Controller
{
    public function __construct(private RequisitionService $service) {}

    /**
     * Display a listing of pending requisitions.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $pending = Requisition::with('items', 'creator')->where('status', 'pending')->get();
        return response()->json($pending);
    }

    /**
     * Approve a requisition.
     *
     * @param Requisition $requisition
     * @return JsonResponse
     */
    public function approve(Requisition $requisition): JsonResponse
    {
        $this->service->approve($requisition, request()->user());
        return response()->json(['status' => 'ok']);
    }

    /**
     * Reject a requisition.
     *
     * @param Requisition $requisition
     * @param Request $request
     * @return JsonResponse
     */
    public function reject(Requisition $requisition, Request $request): JsonResponse
    {
        $request->validate(['reason' => 'required|string']);
        $this->service->reject($requisition, request()->user(), $request->reason);
        return response()->json(['status' => 'ok']);
    }

    /**
     * Get all approved requisitions. and not completed.
     *
     * @return JsonResponse
     */
    public function approved()
    {
        $requisitions = Requisition::with(['creator', 'items'])
            ->where('status', 'approved')
            ->where('status', '!=', 'completed')
            ->latest()
            ->get();

        return response()->json($requisitions);
    }
}
