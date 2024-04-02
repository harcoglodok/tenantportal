<?php

namespace App\Repositories;

use App\Models\Complaint;
use App\Repositories\BaseRepository;

/**
 * Class ComplaintRepository
 * @package App\Repositories
 * @version April 2, 2024, 7:08 am UTC
*/

class ComplaintRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'content',
        'photo',
        'created_by',
        'updated_by'
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
        return Complaint::class;
    }
}
