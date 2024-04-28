<?php

namespace App\Repositories;

use App\Models\ComplaintCategory;
use App\Repositories\BaseRepository;

/**
 * Class ComplaintCategoryRepository
 * @package App\Repositories
 * @version April 2, 2024, 7:00 am UTC
*/

class ComplaintCategoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title'
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
        return ComplaintCategory::class;
    }

    /**
     * @var array
     */
    protected $relations = [];

    /**
     * Return relations
     *
     * @return array
     */
    public function getRelations()
    {
        return $this->relations;
    }

}
