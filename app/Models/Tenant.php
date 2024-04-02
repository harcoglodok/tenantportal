<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class Tenant
 * @package App\Models
 * @version April 2, 2024, 9:09 am UTC
 *
 * @property string $no_unit
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property string $number
 */
class Tenant extends Model
{
    use SoftDeletes;


    public $table = 'tenants';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'no_unit',
        'name',
        'phone',
        'email',
        'number'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'no_unit' => 'string',
        'name' => 'string',
        'phone' => 'string',
        'email' => 'string',
        'number' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'no_unit' => 'required',
        'name' => 'required',
        'phone' => 'required',
        'email' => 'required',
        'number' => 'required'
    ];

    
}