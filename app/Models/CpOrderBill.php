<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CpOrderBill extends Model
{
    protected $fillable = [
        'cp_order_id',
        'cp_id',
        'file_path',
        'file_name',
        'file_size',
        'remarks',
        'uploaded_by',
    ];

    public function cpOrder()
    {
        return $this->belongsTo(CpOrder::class, 'cp_order_id');
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
