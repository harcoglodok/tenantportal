<?php

namespace App\Repositories;

use App\Models\Tenant;
use App\Repositories\BaseRepository;

/**
 * Class TenantRepository
 * @package App\Repositories
 * @version April 2, 2024, 9:09 am UTC
*/

class TenantRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'no_unit',
        'name',
        'phone',
        'email',
        'number'
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
        return Tenant::class;
    }
}
