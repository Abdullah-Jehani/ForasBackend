<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'address', 'phone', 'image', 'bio', 'title'];

    public function User() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
