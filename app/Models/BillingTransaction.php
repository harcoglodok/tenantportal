<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class BillingTransaction
 * @package App\Models
 * @version April 2, 2024, 9:51 am UTC
 *
 * @property integer $billing_id
 * @property integer $user_id
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
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:8192'
    ];

    /**
     * Get the verifiedBy that owns the BillingTransaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
