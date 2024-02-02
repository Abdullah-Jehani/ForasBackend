<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Company extends Model implements Authenticatable
{
    use AuthenticatableTrait;
    use HasFactory;
    use HasApiTokens;

    protected $fillable = ['name', 'email', 'password'];

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function CompanyDetails()
    {
        return $this->hasOne(CompanyDetail::class, 'company_id');
    }
}
