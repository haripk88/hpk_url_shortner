<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Vinkla\Hashids\Facades\Hashids;

class UrlShortner extends Model
{
    use HasFactory;
    protected $fillable = ['company_id', 'url', 'created_by'];

    protected $appends = ['short_url'];

    protected $casts = [
        'hits' => 'integer',
    ];

    public function getShortUrlAttribute()
    {
        // return $this->id;
        return route('short.redirect', ['code' => Hashids::encode($this->id)]);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
