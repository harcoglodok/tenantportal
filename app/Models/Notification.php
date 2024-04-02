<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


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
        'date'
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
        'date' => 'date'
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
        'date' => 'required'
    ];

    
}
