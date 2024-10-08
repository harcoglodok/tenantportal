<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class ComplaintReply
 * @package App\Models
 * @version April 2, 2024, 7:18 am UTC
 *
 * @property integer $complaint_id
 * @property string $reply
 * @property string $status
 * @property integer $user_id
 */
class ComplaintReply extends Model
{
    use SoftDeletes;


    public $table = 'complaint_replies';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'complaint_id',
        'reply',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'reply' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'complaint_id' => 'required',
        'reply' => 'required',
        'user_id' => 'required'
    ];

    /**
     * Get the user that owns the ComplaintReply
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
