<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class Billing
 * @package App\Models
 * @version April 2, 2024, 9:36 am UTC
 *
 * @property string $inv_no
 * @property string $month
 * @property string $year
 * @property foreignId $tenant_id
 * @property number $s4_mbase_amt
 * @property number $s4_mtax_amt
 * @property number $sd_mbase_amt
 * @property number $service_charge
 * @property number $sinking_fund
 * @property number $electric_previous
 * @property number $electric_current
 * @property number $electric_read
 * @property number $electric_fixed
 * @property number $electric_administration
 * @property number $electric_tax
 * @property number $electric_total
 * @property number $mcb
 * @property number $water_previous
 * @property number $water_current
 * @property number $water_read
 * @property number $water_fixed
 * @property number $water_mbase
 * @property number $water_administration
 * @property number $water_tax
 * @property number $water_total
 * @property number $total
 * @property string $tube
 * @property string $panin
 * @property string $bca
 * @property string $cimb
 * @property string $mandiri
 * @property number $add_charge
 * @property number $previous_transaction
 * @property string $status
 */
class Billing extends Model
{
    use SoftDeletes;


    public $table = 'billings';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'inv_no',
        'month',
        'year',
        'tenant_id',
        's4_mbase_amt',
        's4_mtax_amt',
        'sd_mbase_amt',
        'service_charge',
        'sinking_fund',
        'electric_previous',
        'electric_current',
        'electric_read',
        'electric_fixed',
        'electric_administration',
        'electric_tax',
        'electric_total',
        'mcb',
        'water_previous',
        'water_current',
        'water_read',
        'water_fixed',
        'water_mbase',
        'water_administration',
        'water_tax',
        'water_total',
        'total',
        'tube',
        'panin',
        'bca',
        'cimb',
        'mandiri',
        'add_charge',
        'previous_transaction',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'inv_no' => 'string',
        'month' => 'string',
        'year' => 'string',
        's4_mbase_amt' => 'double',
        's4_mtax_amt' => 'double',
        'sd_mbase_amt' => 'double',
        'service_charge' => 'double',
        'sinking_fund' => 'double',
        'electric_previous' => 'double',
        'electric_current' => 'double',
        'electric_read' => 'double',
        'electric_fixed' => 'double',
        'electric_administration' => 'double',
        'electric_tax' => 'double',
        'electric_total' => 'double',
        'mcb' => 'double',
        'water_previous' => 'double',
        'water_current' => 'double',
        'water_read' => 'double',
        'water_fixed' => 'double',
        'water_mbase' => 'double',
        'water_administration' => 'double',
        'water_tax' => 'double',
        'water_total' => 'double',
        'total' => 'double',
        'tube' => 'string',
        'panin' => 'string',
        'bca' => 'string',
        'cimb' => 'string',
        'mandiri' => 'string',
        'add_charge' => 'double',
        'previous_transaction' => 'double',
        'status' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'inv_no' => 'required',
        'month' => 'required',
        'year' => 'required',
        'tenant_id' => 'required'
    ];
}
