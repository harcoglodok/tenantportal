<?php

namespace App\Repositories;

use App\Models\Notification;
use App\Repositories\BaseRepository;

/**
 * Class NotificationRepository
 * @package App\Repositories
 * @version April 2, 2024, 8:06 am UTC
*/

class NotificationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'notification_category_id',
        'title',
        'image',
        'message',
        'date'
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
        return Notification::class;
    }
}
