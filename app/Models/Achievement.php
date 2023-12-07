<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'threshold'];

    /** 
     * The users that have this achievement.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}