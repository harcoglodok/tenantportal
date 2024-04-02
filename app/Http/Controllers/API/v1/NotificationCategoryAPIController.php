<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Requests\API\CreateNotificationCategoryAPIRequest;
use App\Http\Requests\API\UpdateNotificationCategoryAPIRequest;
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
    private $notificationCategoryRepository;

    public function __construct(NotificationCategoryRepository $notificationCategoryRepo)
    {
        $this->notificationCategoryRepository = $notificationCategoryRepo;
    }

    /**
     * Display a listing of the NotificationCategory.
     * GET|HEAD /notificationCategories
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $notificationCategories = $this->notificationCategoryRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(NotificationCategoryResource::collection($notificationCategories), 'Notification Categories retrieved successfully');
    }

    /**
     * Store a newly created NotificationCategory in storage.
     * POST /notificationCategories
     *
     * @param CreateNotificationCategoryAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateNotificationCategoryAPIRequest $request)
    {
        $input = $request->all();

        $notificationCategory = $this->notificationCategoryRepository->create($input);

        return $this->sendResponse(new NotificationCategoryResource($notificationCategory), 'Notification Category saved successfully');
    }

    /**
     * Display the specified NotificationCategory.
     * GET|HEAD /notificationCategories/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var NotificationCategory $notificationCategory */
        $notificationCategory = $this->notificationCategoryRepository->find($id);

        if (empty($notificationCategory)) {
            return $this->sendError('Notification Category not found');
        }

        return $this->sendResponse(new NotificationCategoryResource($notificationCategory), 'Notification Category retrieved successfully');
    }

    /**
     * Update the specified NotificationCategory in storage.
     * PUT/PATCH /notificationCategories/{id}
     *
     * @param int $id
     * @param UpdateNotificationCategoryAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateNotificationCategoryAPIRequest $request)
    {
        $input = $request->all();

        /** @var NotificationCategory $notificationCategory */
        $notificationCategory = $this->notificationCategoryRepository->find($id);

        if (empty($notificationCategory)) {
            return $this->sendError('Notification Category not found');
        }

        $notificationCategory = $this->notificationCategoryRepository->update($input, $id);

        return $this->sendResponse(new NotificationCategoryResource($notificationCategory), 'NotificationCategory updated successfully');
    }

    /**
     * Remove the specified NotificationCategory from storage.
     * DELETE /notificationCategories/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var NotificationCategory $notificationCategory */
        $notificationCategory = $this->notificationCategoryRepository->find($id);

        if (empty($notificationCategory)) {
            return $this->sendError('Notification Category not found');
        }

        $notificationCategory->delete();

        return $this->sendSuccess('Notification Category deleted successfully');
    }
}
