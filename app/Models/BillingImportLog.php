<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class BillingImportLog
 * @package App\Models
 * @version April 2, 2024, 10:03 am UTC
 *
 * @property foreignId $user_id
 * @property string $file
 */
class BillingImportLog extends Model
{
    use SoftDeletes;


    public $table = 'billing_import_logs';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'user_id',
        'file'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'file' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required',
        'file' => 'required'
    ];

    
}
