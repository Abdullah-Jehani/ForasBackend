<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class companyDetail extends Model
{
    use HasFactory;
    protected $fillable = ['phone_number', 'location', 'category', 'logo'];
    public function company() {
        return $this->belongsTo(Company::class);
    }
}
