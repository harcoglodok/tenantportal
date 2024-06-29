<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * Class Unit
 * @package App\Models
 * @version April 2, 2024, 9:09 am UTC
 *
 * @property string $no_unit
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property string $number
 */
class Unit extends Model
{
    use SoftDeletes;


    public $table = 'units';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'id',
        'no_unit',
        'business_id',
        'name',
        'phone',
        'email',
        'number',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'no_unit' => 'string',
        'business_id' => 'string',
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

    /**
     * Get the user that owns the Tenant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the billings for the Unit
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function billings(): HasMany
    {
        return $this->hasMany(Billing::class, 'unit_no', 'no_unit');
    }

    /**
     * Get all of the complaints for the Unit
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }
}
