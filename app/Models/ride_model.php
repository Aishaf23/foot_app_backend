<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ride extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'buddy_id',
        'type',
        'status',
        'pickup_location',
        'drop_location',
        'notes',
        'fare',
    ];

    // Relationship: Ride belongs to User
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Relationship: Ride belongs to Buddy
    public function buddy() {
        return $this->belongsTo(User::class, 'buddy_id');
    }
}
