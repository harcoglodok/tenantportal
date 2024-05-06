<?php

namespace App\Http\Controllers\API\v1;

use Response;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\BillingTransaction;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\BillingTransactionResource;
use App\Repositories\BillingTransactionRepository;
use App\Http\Requests\API\CreateBillingTransactionAPIRequest;
use App\Http\Requests\API\UpdateBillingTransactionAPIRequest;

/**
 * Class BillingTransactionController
 * @package App\Http\Controllers\API\v1
 */

class BillingTransactionAPIController extends AppBaseController
{
    /** @var  BillingTransactionRepository */
    private $billingTransactionRepository;

    public function __construct(BillingTransactionRepository $billingTransactionRepo)
    {
        $this->billingTransactionRepository = $billingTransactionRepo;
    }

    /**
     * Display a listing of the BillingTransaction.
     * GET|HEAD /billingTransactions
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $billingTransactions = $this->billingTransactionRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit'),
            ['*'],
            $request->get('sort_by'),
            $request->get('sort_direction', 'asc'),
        );

        return $this->sendResponse(BillingTransactionResource::collection($billingTransactions), 'Billing Transactions retrieved successfully');
    }

    /**
     * Store a newly created BillingTransaction in storage.
     * POST /billingTransactions
     *
     * @param CreateBillingTransactionAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateBillingTransactionAPIRequest $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $request->merge([
            'user_id' => $user->id,
            'status' => 'pending',
            'message' => 'pending',
        ]);
        $input = $request->all();
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $this->fileUpload('transactions', $image);
            $input['image'] = $imagePath;
        }

        $billingTransaction = $this->billingTransactionRepository->create($input);
        $admins = User::whereIn('role', ['root', 'admin'])->get();
        if ($admins) {
            Notification::make()
                ->title('User ' . $user->name . ' melakukan update data')
                ->sendToDatabase($admins);
        }
        return $this->sendResponse(new BillingTransactionResource($billingTransaction), 'Billing Transaction saved successfully');
    }

    /**
     * Display the specified BillingTransaction.
     * GET|HEAD /billingTransactions/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var BillingTransaction $billingTransaction */
        $billingTransaction = $this->billingTransactionRepository->find($id);

        if (empty($billingTransaction)) {
            return $this->sendError('Billing Transaction not found');
        }

        return $this->sendResponse(new BillingTransactionResource($billingTransaction), 'Billing Transaction retrieved successfully');
    }

    /**
     * Update the specified BillingTransaction in storage.
     * PUT/PATCH /billingTransactions/{id}
     *
     * @param int $id
     * @param UpdateBillingTransactionAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBillingTransactionAPIRequest $request)
    {
        $input = $request->all();

        /** @var BillingTransaction $billingTransaction */
        $billingTransaction = $this->billingTransactionRepository->find($id);

        if (empty($billingTransaction)) {
            return $this->sendError('Billing Transaction not found');
        }

        $billingTransaction = $this->billingTransactionRepository->update($input, $id);

        return $this->sendResponse(new BillingTransactionResource($billingTransaction), 'BillingTransaction updated successfully');
    }

    /**
     * Remove the specified BillingTransaction from storage.
     * DELETE /billingTransactions/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var BillingTransaction $billingTransaction */
        $billingTransaction = $this->billingTransactionRepository->find($id);

        if (empty($billingTransaction)) {
            return $this->sendError('Billing Transaction not found');
        }

        $billingTransaction->delete();

        return $this->sendSuccess('Billing Transaction deleted successfully');
    }
}
