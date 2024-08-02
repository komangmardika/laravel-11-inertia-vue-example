<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'position_name',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_positions', 'position_id', 'user_id');
    }
}
