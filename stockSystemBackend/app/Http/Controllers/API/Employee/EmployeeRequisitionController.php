<?php

namespace App\Http\Controllers\API\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequisitionRequest;
use App\Services\RequisitionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as HttpResponse;

class EmployeeRequisitionController extends Controller
{
    /**
     * EmployeeRequisitionController constructor.
     *
     * @param RequisitionService $service
     */
    public function __construct(private RequisitionService $service) {}

    /**
     * Store a new requisition.
     *
     * @param StoreRequisitionRequest $request
     * @return JsonResponse
     */
    public function store(StoreRequisitionRequest $request): JsonResponse
    {
        $req = $this->service->create($request->user(), $request->validated());
        return response()->json($req->load('items'), HttpResponse::HTTP_CREATED);
    }

    /**
     * Display a listing of the user's requisitions.
     *
     * @return JsonResponse
     */
    public function my(): JsonResponse
    {
        $list = auth()->user()->requisitions()->with('items')->get();
        return response()->json($list, HttpResponse::HTTP_OK);
    }
}
