<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'business_unit',
        'sales_name',
        'end_user', 
        'pic_engineering', 
        'email_engineering',
        'description',
        'inquiry_date',
        'status',
        'document'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class)->withDefault();
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }
}
