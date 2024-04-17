<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Requests\API\CreateUnitAPIRequest;
use App\Http\Requests\API\UpdateUnitAPIRequest;
use App\Models\Unit;
use App\Repositories\UnitRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\UnitResource;
use Response;

/**
 * Class UnitController
 * @package App\Http\Controllers\API\v1
 */

class UnitAPIController extends AppBaseController
{
    /** @var  UnitRepository */
    private $tenantRepository;

    public function __construct(UnitRepository $tenantRepo)
    {
        $this->tenantRepository = $tenantRepo;
    }

    /**
     * Display a listing of the Unit.
     * GET|HEAD /tenants
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $tenants = $this->tenantRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(UnitResource::collection($tenants), 'Units retrieved successfully');
    }

    /**
     * Store a newly created Unit in storage.
     * POST /tenants
     *
     * @param CreateUnitAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateUnitAPIRequest $request)
    {
        $input = $request->all();

        $tenant = $this->tenantRepository->create($input);

        return $this->sendResponse(new UnitResource($tenant), 'Unit saved successfully');
    }

    /**
     * Display the specified Unit.
     * GET|HEAD /tenants/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Unit $tenant */
        $tenant = $this->tenantRepository->find($id);

        if (empty($tenant)) {
            return $this->sendError('Unit not found');
        }

        return $this->sendResponse(new UnitResource($tenant), 'Unit retrieved successfully');
    }

    /**
     * Update the specified Unit in storage.
     * PUT/PATCH /tenants/{id}
     *
     * @param int $id
     * @param UpdateUnitAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUnitAPIRequest $request)
    {
        $input = $request->all();

        /** @var Unit $tenant */
        $tenant = $this->tenantRepository->find($id);

        if (empty($tenant)) {
            return $this->sendError('Unit not found');
        }

        $tenant = $this->tenantRepository->update($input, $id);

        return $this->sendResponse(new UnitResource($tenant), 'Unit updated successfully');
    }

    /**
     * Remove the specified Unit from storage.
     * DELETE /tenants/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Unit $tenant */
        $tenant = $this->tenantRepository->find($id);

        if (empty($tenant)) {
            return $this->sendError('Unit not found');
        }

        $tenant->delete();

        return $this->sendSuccess('Unit deleted successfully');
    }
}
