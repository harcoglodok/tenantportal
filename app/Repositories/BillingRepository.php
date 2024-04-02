<?php

namespace App\Repositories;

use App\Models\Billing;
use App\Repositories\BaseRepository;

/**
 * Class BillingRepository
 * @package App\Repositories
 * @version April 2, 2024, 9:36 am UTC
*/

class BillingRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'inv_no',
        'month',
        'year',
        'tenant_id',
        's4_mbase_amt',
        's4_mtax_amt',
        'sd_mbase_amt',
        'service_charge',
        'sinking_fund',
        'electric_previous',
        'electric_current',
        'electric_read',
        'electric_fixed',
        'electric_administration',
        'electric_tax',
        'electric_total',
        'mcb',
        'water_previous',
        'water_current',
        'water_read',
        'water_fixed',
        'water_mbase',
        'water_administration',
        'water_tax',
        'water_total',
        'total',
        'tube',
        'panin',
        'bca',
        'cimb',
        'mandiri',
        'add_charge',
        'previous_transaction',
        'status'
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
        return Billing::class;
    }
}