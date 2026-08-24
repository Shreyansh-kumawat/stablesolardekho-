<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerRfq extends Model
{
    protected $fillable = [
        'user_id', 'name', 'phone', 'email', 'city',
        'item_description', 'quantity', 'preferred_brand', 'additional_notes',
        'status', 'product_id', 'quoted_price', 'discount_percent', 'final_price',
        'admin_remarks', 'processed_by', 'quoted_at', 'matches',
    ];

    protected $casts = [
        'quoted_at' => 'datetime',
        'quoted_price' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'final_price' => 'decimal:2',
        'matches' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
