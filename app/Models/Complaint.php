<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class Complaint
 * @package App\Models
 * @version April 2, 2024, 7:08 am UTC
 *
 * @property integer $category_id
 * @property string $content
 * @property string $photo
 * @property string $created_by
 * @property string $updated_by
 */
class Complaint extends Model
{
    use SoftDeletes;


    public $table = 'complaints';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'category_id',
        'title',
        'content',
        'photo',
        'status',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'category_id' => 'integer',
        'content' => 'string',
        'status' => 'string',
        'photo' => 'string',
        'created_by' => 'string',
        'updated_by' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'category_id' => 'required',
        'content' => 'required',
        'status' => 'required',
        'photo' => 'image|mimes:jpeg,png,jpg,gif|max:8192',
        'created_by' => 'required',
        'updated_by' => 'required'
    ];
}
