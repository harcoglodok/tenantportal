<?php

namespace App\Http\Controllers\API\v1;

use Response;
use App\Models\User;
use App\Models\Complaint;
use Illuminate\Http\Request;
use App\Models\ComplaintReply;
use Filament\Notifications\Notification;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ComplaintReplyResource;
use App\Repositories\ComplaintReplyRepository;
use App\Http\Requests\API\CreateComplaintReplyAPIRequest;
use App\Http\Requests\API\UpdateComplaintReplyAPIRequest;

/**
 * Class ComplaintReplyController
 * @package App\Http\Controllers\API\v1
 */

class ComplaintReplyAPIController extends AppBaseController
{
    /** @var  ComplaintReplyRepository */
    private $complaintReplyRepository;

    public function __construct(ComplaintReplyRepository $complaintReplyRepo)
    {
        $this->complaintReplyRepository = $complaintReplyRepo;
    }

    /**
     * Display a listing of the ComplaintReply.
     * GET|HEAD /complaintReplies
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $complaintReplies = $this->complaintReplyRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit'),
            ['*'],
            $request->get('sort_by'),
            $request->get('sort_direction', 'asc'),
        );

        return $this->sendResponse(ComplaintReplyResource::collection($complaintReplies), 'Complaint Replies retrieved successfully');
    }

    /**
     * Store a newly created ComplaintReply in storage.
     * POST /complaintReplies
     *
     * @param CreateComplaintReplyAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateComplaintReplyAPIRequest $request)
    {
        $input = $request->all();

        $complaintReply = $this->complaintReplyRepository->create($input);

        $complaint = Complaint::find($complaintReply->complaint_id);
        $complaint->update(['status' => 'waiting']);

        $admins = User::whereIn('role', ['root', 'admin'])->get();
        if ($admins) {
            Notification::make()
                ->title('Terdapat balasan komplain dari ' . $complaintReply->user->name)
                ->sendToDatabase($admins);
        }
        return $this->sendResponse(new ComplaintReplyResource($complaintReply), 'Complaint Reply saved successfully');
    }

    /**
     * Display the specified ComplaintReply.
     * GET|HEAD /complaintReplies/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ComplaintReply $complaintReply */
        $complaintReply = $this->complaintReplyRepository->find($id);

        if (empty($complaintReply)) {
            return $this->sendError('Complaint Reply not found');
        }

        return $this->sendResponse(new ComplaintReplyResource($complaintReply), 'Complaint Reply retrieved successfully');
    }

    /**
     * Update the specified ComplaintReply in storage.
     * PUT/PATCH /complaintReplies/{id}
     *
     * @param int $id
     * @param UpdateComplaintReplyAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateComplaintReplyAPIRequest $request)
    {
        $input = $request->all();

        /** @var ComplaintReply $complaintReply */
        $complaintReply = $this->complaintReplyRepository->find($id);

        if (empty($complaintReply)) {
            return $this->sendError('Complaint Reply not found');
        }

        $complaintReply = $this->complaintReplyRepository->update($input, $id);

        return $this->sendResponse(new ComplaintReplyResource($complaintReply), 'ComplaintReply updated successfully');
    }

    /**
     * Remove the specified ComplaintReply from storage.
     * DELETE /complaintReplies/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ComplaintReply $complaintReply */
        $complaintReply = $this->complaintReplyRepository->find($id);

        if (empty($complaintReply)) {
            return $this->sendError('Complaint Reply not found');
        }

        $complaintReply->delete();

        return $this->sendSuccess('Complaint Reply deleted successfully');
    }
}
