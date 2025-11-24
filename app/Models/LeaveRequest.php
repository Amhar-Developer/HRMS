<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequest extends Model
{
    protected $fillable = ['user_id', 'leave_type_id', 'start_date', 'end_date', 'reason', 'status', 'days', 'reject_reason', 'approved_at', 'approved_by'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
