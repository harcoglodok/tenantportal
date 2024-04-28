<?php

namespace App\Http\Controllers\API\v1;

use App\Models\NotificationCategory;
use App\Repositories\NotificationCategoryRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\NotificationCategoryResource;
use Response;

/**
 * Class NotificationCategoryController
 * @package App\Http\Controllers\API\v1
 */

class NotificationCategoryAPIController extends AppBaseController
{
    /** @var  NotificationCategoryRepository */
    private $complaintCategoryRepository;

    public function __construct(NotificationCategoryRepository $complaintCategoryRepo)
    {
        $this->complaintCategoryRepository = $complaintCategoryRepo;
    }

    /**
     * Display a listing of the NotificationCategory.
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

        return $this->sendResponse(NotificationCategoryResource::collection($complaintCategories), 'Notification Categories retrieved successfully');
    }

    /**
     * Store a newly created NotificationCategory in storage.
     * POST /complaintCategories
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $complaintCategory = $this->complaintCategoryRepository->create($input);

        return $this->sendResponse(new NotificationCategoryResource($complaintCategory), 'Notification Category saved successfully');
    }

    /**
     * Display the specified NotificationCategory.
     * GET|HEAD /complaintCategories/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var NotificationCategory $complaintCategory */
        $complaintCategory = $this->complaintCategoryRepository->find($id);

        if (empty($complaintCategory)) {
            return $this->sendError('Notification Category not found');
        }

        return $this->sendResponse(new NotificationCategoryResource($complaintCategory), 'Notification Category retrieved successfully');
    }

    /**
     * Update the specified NotificationCategory in storage.
     * PUT/PATCH /complaintCategories/{id}
     *
     * @param int $id
     * @param Request $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        $input = $request->all();

        /** @var NotificationCategory $complaintCategory */
        $complaintCategory = $this->complaintCategoryRepository->find($id);

        if (empty($complaintCategory)) {
            return $this->sendError('Notification Category not found');
        }

        $complaintCategory = $this->complaintCategoryRepository->update($input, $id);

        return $this->sendResponse(new NotificationCategoryResource($complaintCategory), 'NotificationCategory updated successfully');
    }

    /**
     * Remove the specified NotificationCategory from storage.
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
        /** @var NotificationCategory $complaintCategory */
        $complaintCategory = $this->complaintCategoryRepository->find($id);

        if (empty($complaintCategory)) {
            return $this->sendError('Notification Category not found');
        }

        $complaintCategory->delete();

        return $this->sendSuccess('Notification Category deleted successfully');
    }
}
