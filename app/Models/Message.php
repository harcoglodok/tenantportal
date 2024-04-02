<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class Message
 * @package App\Models
 * @version April 2, 2024, 7:50 am UTC
 *
 * @property string $title
 * @property string $created_by
 * @property string $updated_by
 */
class Message extends Model
{
    use SoftDeletes;


    public $table = 'messages';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'title',
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
        'created_by' => 'string',
        'updated_by' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'required',
        'created_by' => 'required',
        'updated_by' => 'required'
    ];

    
}
