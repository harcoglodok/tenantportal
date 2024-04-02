<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class BillingTransaction
 * @package App\Models
 * @version April 2, 2024, 9:51 am UTC
 *
 * @property foreignId $billing_id
 * @property foreignId $user_id
 * @property string $image
 * @property string $status
 * @property string $message
 */
class BillingTransaction extends Model
{
    use SoftDeletes;


    public $table = 'billing_transactions';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'billing_id',
        'user_id',
        'image',
        'status',
        'message'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'image' => 'string',
        'status' => 'string',
        'message' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'billing_id' => 'required',
        'user_id' => 'required',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:8192',
        'status' => 'required'
    ];
}
