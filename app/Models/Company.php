<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $fillable = ['email', 'company_name', 'password'];

    public function companyDetails() {
        return $this->hasOne(companyDetail::class);
    }
}
