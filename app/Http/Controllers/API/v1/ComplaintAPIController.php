<?php

namespace App\Http\Controllers\API\v1;

use Response;
use App\Models\User;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Filament\Notifications\Notification;
use App\Http\Resources\ComplaintResource;
use App\Repositories\ComplaintRepository;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateComplaintAPIRequest;
use App\Http\Requests\API\UpdateComplaintAPIRequest;

/**
 * Class ComplaintController
 * @package App\Http\Controllers\API\v1
 */

class ComplaintAPIController extends AppBaseController
{
    /** @var  ComplaintRepository */
    private $complaintRepository;

    public function __construct(ComplaintRepository $complaintRepo)
    {
        $this->complaintRepository = $complaintRepo;
    }

    /**
     * Display a listing of the Complaint.
     * GET|HEAD /complaints
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $request->merge(['user_id' => auth()->user()->id]);
        $complaints = $this->complaintRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit'),
            ['*'],
            $request->get('sort_by'),
            $request->get('sort_direction', 'asc'),
        );

        return $this->sendResponse(ComplaintResource::collection($complaints), 'Complaints retrieved successfully');
    }

    /**
     * Store a newly created Complaint in storage.
     * POST /complaints
     *
     * @param CreateComplaintAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateComplaintAPIRequest $request)
    {
        $request->merge([
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
            'status' => 'waiting',
        ]);
        $input = $request->all();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $this->fileUpload('complaints', $image);
            $input['photo'] = $imagePath;
        }

        $complaint = $this->complaintRepository->create($input);

        $admins = User::whereIn('role', ['root', 'admin'])->get();
        if ($admins) {
            Notification::make()
                ->title('Terdapat komplain baru dari ' . $complaint->createdBy->name)
                ->sendToDatabase($admins);
        }

        return $this->sendResponse(new ComplaintResource($complaint), 'Complaint saved successfully');
    }

    /**
     * Display the specified Complaint.
     * GET|HEAD /complaints/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Complaint $complaint */
        $complaint = $this->complaintRepository->find($id);

        if (empty($complaint)) {
            return $this->sendError('Complaint not found');
        }

        return $this->sendResponse(new ComplaintResource($complaint), 'Complaint retrieved successfully');
    }

    /**
     * Update the specified Complaint in storage.
     * PUT/PATCH /complaints/{id}
     *
     * @param int $id
     * @param UpdateComplaintAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateComplaintAPIRequest $request)
    {
        $input = $request->all();

        /** @var Complaint $complaint */
        $complaint = $this->complaintRepository->find($id);

        if (empty($complaint)) {
            return $this->sendError('Complaint not found');
        }
        $complaint = $this->complaintRepository->update($input, $id);
        dd($complaint);

        return $this->sendResponse(new ComplaintResource($complaint), 'Complaint updated successfully');
    }

    /**
     * Remove the specified Complaint from storage.
     * DELETE /complaints/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Complaint $complaint */
        $complaint = $this->complaintRepository->find($id);

        if (empty($complaint)) {
            return $this->sendError('Complaint not found');
        }

        $complaint->delete();

        return $this->sendSuccess('Complaint deleted successfully');
    }
}
