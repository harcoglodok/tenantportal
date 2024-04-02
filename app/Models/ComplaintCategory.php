<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class ComplaintCategory
 * @package App\Models
 * @version April 2, 2024, 7:00 am UTC
 *
 * @property string $title
 */
class ComplaintCategory extends Model
{
    use SoftDeletes;


    public $table = 'complaint_categories';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'title'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'title' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'required'
    ];

    
}
