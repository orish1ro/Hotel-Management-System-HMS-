<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    protected $table = 'guest'; 
    protected $primaryKey = 'GUEST_ID';

    // This line tells Laravel that your table does NOT have created_at and updated_at
    public $timestamps = false; 

    protected $fillable = [
        'First_Name', 
        'Last_Name', 
        'Email', 
        'Phone_Number',
        'Password'
    ];
}