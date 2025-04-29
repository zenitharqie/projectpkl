<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $table = 'purchaseorders';

    protected $primaryKey = 'purchaseorder_ID';

    protected $fillable = [
        'QID',
        'PO_Date',
        'Job_Number',
        'Contract_Number',
        'po_value',
        'Upload_File',
        'status',
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'QID');
    }
}
