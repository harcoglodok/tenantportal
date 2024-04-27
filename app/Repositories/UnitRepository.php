<?php

namespace App\Repositories;

use App\Models\Unit;
use App\Repositories\BaseRepository;

/**
 * Class UnitRepository
 * @package App\Repositories
 * @version April 2, 2024, 9:09 am UTC
*/

class UnitRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'no_unit',
        'name',
        'phone',
        'email',
        'number',
        'user_id',
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
        return Unit::class;
    }
}
