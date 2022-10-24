<?php

namespace App\Models;

class Order extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    protected $table = 'orders';

    public $timestamps = true;

    public $decimalFields = [
        "shippingAmount",
        "packingAmount",
        "forwardAmount",
        "printingAmount",
        "discountAmount",
        "grandTotal",
        "subtotal",
        "taxAmount",
        "totalAmount",
        "totalPaid",
        "pendingAmount"
    ];

    const TYPES = [
        'quotation',
        'booking',
        'sales'
    ];
    
}