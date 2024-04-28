<?php

namespace App\Repositories;

use App\Models\ComplaintReply;
use App\Repositories\BaseRepository;

/**
 * Class ComplaintReplyRepository
 * @package App\Repositories
 * @version April 2, 2024, 7:18 am UTC
*/

class ComplaintReplyRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'reply'
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
        return ComplaintReply::class;
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
