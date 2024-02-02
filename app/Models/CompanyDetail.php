<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyDetail extends Model
{
    use HasFactory;
    protected $fillable = ['company_id', 'address', 'phone', 'logo', 'bio', 'category'];
    public function Company() {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
