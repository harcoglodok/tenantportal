<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Requests\API\CreateBillingImportLogAPIRequest;
use App\Http\Requests\API\UpdateBillingImportLogAPIRequest;
use App\Models\BillingImportLog;
use App\Repositories\BillingImportLogRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\BillingImportLogResource;
use Response;

/**
 * Class BillingImportLogController
 * @package App\Http\Controllers\API\v1
 */

class BillingImportLogAPIController extends AppBaseController
{
    /** @var  BillingImportLogRepository */
    private $billingImportLogRepository;

    public function __construct(BillingImportLogRepository $billingImportLogRepo)
    {
        $this->billingImportLogRepository = $billingImportLogRepo;
    }

    /**
     * Display a listing of the BillingImportLog.
     * GET|HEAD /billingImportLogs
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $billingImportLogs = $this->billingImportLogRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(BillingImportLogResource::collection($billingImportLogs), 'Billing Import Logs retrieved successfully');
    }

    /**
     * Store a newly created BillingImportLog in storage.
     * POST /billingImportLogs
     *
     * @param CreateBillingImportLogAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateBillingImportLogAPIRequest $request)
    {
        $input = $request->all();

        $billingImportLog = $this->billingImportLogRepository->create($input);

        return $this->sendResponse(new BillingImportLogResource($billingImportLog), 'Billing Import Log saved successfully');
    }

    /**
     * Display the specified BillingImportLog.
     * GET|HEAD /billingImportLogs/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var BillingImportLog $billingImportLog */
        $billingImportLog = $this->billingImportLogRepository->find($id);

        if (empty($billingImportLog)) {
            return $this->sendError('Billing Import Log not found');
        }

        return $this->sendResponse(new BillingImportLogResource($billingImportLog), 'Billing Import Log retrieved successfully');
    }

    /**
     * Update the specified BillingImportLog in storage.
     * PUT/PATCH /billingImportLogs/{id}
     *
     * @param int $id
     * @param UpdateBillingImportLogAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBillingImportLogAPIRequest $request)
    {
        $input = $request->all();

        /** @var BillingImportLog $billingImportLog */
        $billingImportLog = $this->billingImportLogRepository->find($id);

        if (empty($billingImportLog)) {
            return $this->sendError('Billing Import Log not found');
        }

        $billingImportLog = $this->billingImportLogRepository->update($input, $id);

        return $this->sendResponse(new BillingImportLogResource($billingImportLog), 'BillingImportLog updated successfully');
    }

    /**
     * Remove the specified BillingImportLog from storage.
     * DELETE /billingImportLogs/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var BillingImportLog $billingImportLog */
        $billingImportLog = $this->billingImportLogRepository->find($id);

        if (empty($billingImportLog)) {
            return $this->sendError('Billing Import Log not found');
        }

        $billingImportLog->delete();

        return $this->sendSuccess('Billing Import Log deleted successfully');
    }
}
