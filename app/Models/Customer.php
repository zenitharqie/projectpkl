<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'phone',
        'email'
    ];

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }
}
