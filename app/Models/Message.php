<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;


/**
 * Class Message
 * @package App\Models
 * @version April 2, 2024, 7:50 am UTC
 *
 * @property string $title
 * @property string $created_by
 * @property string $updated_by
 */
class Message extends Model
{
    use SoftDeletes;


    public $table = 'messages';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'title',
        'photo',
        'content',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'title' => 'string',
        'content' => 'string',
        'created_by' => 'string',
        'updated_by' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'required',
        'content' => 'required',
        'created_by' => 'required',
        'updated_by' => 'required'
    ];

    /**
     * Get the createdBy that owns the Message
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Get the updatedBy that owns the Message
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    /**
     * The tenants that belong to the Message
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class, 'tenant_message', 'message_id', 'tenant_id');
    }
}
