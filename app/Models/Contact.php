<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contact extends Model
{
    protected $table = 'contacts';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'user_id',
        'handled_by',
        'status',
        'response',
        'responded_at',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'responded_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function handledByUser()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }
}
