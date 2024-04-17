<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class Complaint
 * @package App\Models
 * @version April 2, 2024, 7:08 am UTC
 *
 * @property integer $category_id
 * @property string $content
 * @property string $photo
 * @property string $created_by
 * @property string $updated_by
 */
class Complaint extends Model
{
    use SoftDeletes;


    public $table = 'complaints';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'category_id',
        'title',
        'content',
        'photo',
        'status',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'category_id' => 'integer',
        'content' => 'string',
        'status' => 'string',
        'photo' => 'string',
        'created_by' => 'string',
        'updated_by' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'category_id' => 'required',
        'content' => 'required',
        'status' => 'required',
        'photo' => 'image|mimes:jpeg,png,jpg,gif|max:8192',
        'created_by' => 'required',
        'updated_by' => 'required'
    ];

    /**
     * Get the category that owns the Complaint
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ComplaintCategory::class, 'category_id');
    }

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
     * Get all of the replies for the Complaint
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies(): HasMany
    {
        return $this->hasMany(ComplaintReply::class);
    }
}
