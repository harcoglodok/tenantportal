<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;


/**
 * Class BillingImportLogData
 * @package App\Models
 * @version April 17, 2024, 10:03 am UTC
 *
 * @property integer $billing_import_log_id
 * @property string $file
 */
class BillingImportLogData extends Model
{

    public $table = 'billing_import_log_data';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'billing_import_log_id',
        'status',
        'message',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'string',
        'message' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'billing_import_log_id' => 'required',
        'status' => 'required',
        'message' => 'required'
    ];


}
