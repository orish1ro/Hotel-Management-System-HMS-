<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';
    protected $primaryKey = 'MESSAGE_ID';

    protected $fillable = [
        'GUEST_ID',
        'STAFF_ID',
        'Message_Text',
        'Admin_Reply',
        'Status',
    ];

    public function guest()
    {
        return $this->belongsTo(Guest::class, 'GUEST_ID', 'GUEST_ID');
    }
}