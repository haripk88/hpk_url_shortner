<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $fillable = [
        'name',
        'email',
        // 'invitation',
        'role',
        'expires_at',
        'status',
        'company_id',
        'used_at',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
