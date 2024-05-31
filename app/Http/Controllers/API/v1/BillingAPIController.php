<?php

namespace App\Http\Controllers\API\v1;

use Response;
use App\Models\Billing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BillingResource;
use App\Repositories\BillingRepository;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateBillingAPIRequest;
use App\Http\Requests\API\UpdateBillingAPIRequest;

/**
 * Class BillingController
 * @package App\Http\Controllers\API\v1
 */

class BillingAPIController extends AppBaseController
{
    /** @var  BillingRepository */
    private $billingRepository;

    public function __construct(BillingRepository $billingRepo)
    {
        $this->billingRepository = $billingRepo;
    }

    /**
     * Display a listing of the Billing.
     * GET|HEAD /billings
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $billings = Billing::when($request->has("unit_no"), function ($query) use ($request) {
            $query->where("unit_no", $request->get("unit_no"));
        })
            ->when($request->has("unit-user_id"), function ($query) use ($request) {
                $query->whereHas('unit', function ($query) use ($request) {
                    $query->where('user_id', $request->get("unit-user_id"));
                });
            })
            ->when($request->has("status"), function ($query) use ($request) {
                $query->where("status", $request->get("status"));
            })
            ->orderBy(DB::raw('CAST(year AS UNSIGNED)'), 'desc')
            ->orderBy(DB::raw('CAST(month AS UNSIGNED)'), 'desc')
            ->get();
        return $this->sendResponse(BillingResource::collection($billings), 'Billings retrieved successfully');
    }

    /**
     * Store a newly created Billing in storage.
     * POST /billings
     *
     * @param CreateBillingAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateBillingAPIRequest $request)
    {
        $input = $request->all();

        $billing = $this->billingRepository->create($input);

        return $this->sendResponse(new BillingResource($billing), 'Billing saved successfully');
    }

    /**
     * Display the specified Billing.
     * GET|HEAD /billings/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Billing $billing */
        $billing = $this->billingRepository->find($id);

        if (empty($billing)) {
            return $this->sendError('Billing not found');
        }

        return $this->sendResponse(new BillingResource($billing), 'Billing retrieved successfully');
    }

    /**
     * Update the specified Billing in storage.
     * PUT/PATCH /billings/{id}
     *
     * @param int $id
     * @param UpdateBillingAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBillingAPIRequest $request)
    {
        $input = $request->all();

        /** @var Billing $billing */
        $billing = $this->billingRepository->find($id);

        if (empty($billing)) {
            return $this->sendError('Billing not found');
        }

        $billing = $this->billingRepository->update($input, $id);

        return $this->sendResponse(new BillingResource($billing), 'Billing updated successfully');
    }

    /**
     * Remove the specified Billing from storage.
     * DELETE /billings/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Billing $billing */
        $billing = $this->billingRepository->find($id);

        if (empty($billing)) {
            return $this->sendError('Billing not found');
        }

        $billing->delete();

        return $this->sendSuccess('Billing deleted successfully');
    }
}
