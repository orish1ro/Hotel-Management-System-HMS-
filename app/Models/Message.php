<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $primaryKey = 'message_id';

    protected $fillable = [
        'guest_id',
        'staff_id',
        'sender_type',
        'message_text',
        'status'
    ];

    // This allows the Staff Dashboard to get the Guest's name automatically
    public function guest()
    {
        return $this->belongsTo(Guest::class, 'guest_id', 'GUEST_ID'); 
    }
}