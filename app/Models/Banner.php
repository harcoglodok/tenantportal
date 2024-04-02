<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class Banner
 * @package App\Models
 * @version April 2, 2024, 6:48 am UTC
 *
 * @property string $title
 * @property string $banner
 * @property boolean $status
 */
class Banner extends Model
{
    use SoftDeletes;


    public $table = 'banners';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'title',
        'banner',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'title' => 'string',
        'banner' => 'string',
        'status' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'required',
        'banner' => 'required|image|mimes:jpeg,png,jpg,gif|max:8192',
        'status' => 'required'
    ];


}
