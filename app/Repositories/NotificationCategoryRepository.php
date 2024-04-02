<?php

namespace App\Repositories;

use App\Models\NotificationCategory;
use App\Repositories\BaseRepository;

/**
 * Class NotificationCategoryRepository
 * @package App\Repositories
 * @version April 2, 2024, 7:53 am UTC
*/

class NotificationCategoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'
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
        return NotificationCategory::class;
    }
}
