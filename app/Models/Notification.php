<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * Class Notification
 * @package App\Models
 * @version April 2, 2024, 8:06 am UTC
 *
 * @property foreignId $notification_category_id
 * @property string $title
 * @property string $image
 * @property string $message
 * @property string $date
 */
class Notification extends Model
{
    use SoftDeletes;


    public $table = 'notifications';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'notification_category_id',
        'title',
        'image',
        'message',
        'date',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'title' => 'string',
        'image' => 'string',
        'message' => 'string',
        'date' => 'date',
        'created_by' => 'string',
        'updated_by' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'notification_category_id' => 'required',
        'title' => 'required',
        'message' => 'required',
        'date' => 'required',
        'created_by' => 'required',
        'updated_by' => 'required'
    ];

    /**
     * Get the createdBy that owns the Message
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Get the updatedBy that owns the Message
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    /**
     * Get the category that owns the Notification
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(NotificationCategory::class, 'notification_category_id', 'id');
    }
}
