<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Checklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'status',
        'deadline',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
