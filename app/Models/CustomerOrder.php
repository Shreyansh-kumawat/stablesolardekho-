<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerOrder extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'total_amount', 'payment_method',
        'payment_status', 'razorpay_order_id', 'razorpay_payment_id',
        'payment_screenshot', 'payment_reference',
        'status', 'name', 'phone', 'address', 'city', 'district', 'state', 'pincode', 'notes',
        'viewed_by_admin',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CustomerOrderItem::class, 'order_id');
    }

    public static function generateOrderNumber()
    {
        return 'ORD-' . strtoupper(uniqid());
    }

    public function getStatusBadgeColor()
    {
        return match($this->status) {
            'pending'   => '#f59e0b',
            'confirmed' => '#3b82f6',
            'shipped'   => '#8b5cf6',
            'delivered' => '#10b981',
            'cancelled' => '#ef4444',
            default     => '#94a3b8',
        };
    }
}
