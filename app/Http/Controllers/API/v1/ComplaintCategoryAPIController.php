<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Requests\API\CreateComplaintCategoryAPIRequest;
use App\Http\Requests\API\UpdateComplaintCategoryAPIRequest;
use App\Models\ComplaintCategory;
use App\Repositories\ComplaintCategoryRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ComplaintCategoryResource;
use Response;

/**
 * Class ComplaintCategoryController
 * @package App\Http\Controllers\API\v1
 */

class ComplaintCategoryAPIController extends AppBaseController
{
    /** @var  ComplaintCategoryRepository */
    private $complaintCategoryRepository;

    public function __construct(ComplaintCategoryRepository $complaintCategoryRepo)
    {
        $this->complaintCategoryRepository = $complaintCategoryRepo;
    }

    /**
     * Display a listing of the ComplaintCategory.
     * GET|HEAD /complaintCategories
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $complaintCategories = $this->complaintCategoryRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit'),
            ['*'],
            $request->get('sort_by'),
            $request->get('sort_direction', 'asc'),
        );

        return $this->sendResponse(ComplaintCategoryResource::collection($complaintCategories), 'Complaint Categories retrieved successfully');
    }

    /**
     * Store a newly created ComplaintCategory in storage.
     * POST /complaintCategories
     *
     * @param CreateComplaintCategoryAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateComplaintCategoryAPIRequest $request)
    {
        $input = $request->all();

        $complaintCategory = $this->complaintCategoryRepository->create($input);

        return $this->sendResponse(new ComplaintCategoryResource($complaintCategory), 'Complaint Category saved successfully');
    }

    /**
     * Display the specified ComplaintCategory.
     * GET|HEAD /complaintCategories/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ComplaintCategory $complaintCategory */
        $complaintCategory = $this->complaintCategoryRepository->find($id);

        if (empty($complaintCategory)) {
            return $this->sendError('Complaint Category not found');
        }

        return $this->sendResponse(new ComplaintCategoryResource($complaintCategory), 'Complaint Category retrieved successfully');
    }

    /**
     * Update the specified ComplaintCategory in storage.
     * PUT/PATCH /complaintCategories/{id}
     *
     * @param int $id
     * @param UpdateComplaintCategoryAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateComplaintCategoryAPIRequest $request)
    {
        $input = $request->all();

        /** @var ComplaintCategory $complaintCategory */
        $complaintCategory = $this->complaintCategoryRepository->find($id);

        if (empty($complaintCategory)) {
            return $this->sendError('Complaint Category not found');
        }

        $complaintCategory = $this->complaintCategoryRepository->update($input, $id);

        return $this->sendResponse(new ComplaintCategoryResource($complaintCategory), 'ComplaintCategory updated successfully');
    }

    /**
     * Remove the specified ComplaintCategory from storage.
     * DELETE /complaintCategories/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ComplaintCategory $complaintCategory */
        $complaintCategory = $this->complaintCategoryRepository->find($id);

        if (empty($complaintCategory)) {
            return $this->sendError('Complaint Category not found');
        }

        $complaintCategory->delete();

        return $this->sendSuccess('Complaint Category deleted successfully');
    }
}
