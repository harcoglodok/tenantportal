<?php

namespace App\Repositories;

use App\Models\BillingImportLog;
use App\Repositories\BaseRepository;

/**
 * Class BillingImportLogRepository
 * @package App\Repositories
 * @version April 2, 2024, 10:03 am UTC
*/

class BillingImportLogRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'file'
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
        return BillingImportLog::class;
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
