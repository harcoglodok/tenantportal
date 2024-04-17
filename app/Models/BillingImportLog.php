<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    protected $appends = [
        'successCount',
        'failedCount',
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

    /**
     * Get the user that owns the BillingImportLog
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function billingImportLogData()
    {
        return $this->hasMany(BillingImportLogData::class, 'billing_import_log_id', 'id');
    }

    public function billingImportLogDataSuccess()
    {
        return $this->hasMany(BillingImportLogData::class, 'billing_import_log_id', 'id')->where('status', 'success');
    }

    public function billingImportLogDataFailed()
    {
        return $this->hasMany(BillingImportLogData::class, 'billing_import_log_id', 'id')->where('status', 'failed');
    }

    public function getSuccessCountAttribute()
    {
        return $this->billingImportLogDataSuccess()->count();
    }

    public function getFailedCountAttribute()
    {
        return $this->billingImportLogDataFailed()->count();
    }
}
