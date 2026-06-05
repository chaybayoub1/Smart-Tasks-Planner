<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollaborationGroupInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'invited_by',
        'email',
        'token',
        'status',
        'expires_at',
        'responded_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    public function group()
    {
        return $this->belongsTo(CollaborationGroup::class, 'group_id');
    }

    public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending'
            && (! $this->expires_at || $this->expires_at->isFuture());
    }
}
