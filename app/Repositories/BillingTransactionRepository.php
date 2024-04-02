<?php

namespace App\Repositories;

use App\Models\BillingTransaction;
use App\Repositories\BaseRepository;

/**
 * Class BillingTransactionRepository
 * @package App\Repositories
 * @version April 2, 2024, 9:51 am UTC
*/

class BillingTransactionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'billing_id',
        'user_id',
        'image',
        'status',
        'message'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return BillingTransaction::class;
    }
}
