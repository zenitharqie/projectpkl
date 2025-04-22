<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'inquiry_id',
        'customer_id',
        'inquiry_date',
        'due_date',
        'quotation_file',
        'status_quotation',
        'email_customer',
        'sales',
        'quotation_number',
        'email_sent_at'
    ];

    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class, 'inquiry_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class)->withDefault();
    }

    // Removed inquiry() relationship as inquiry_id column is removed
    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }
}

class QuotationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_id',
        'name',
        'quantity',
        'price',
        'total',
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }
}