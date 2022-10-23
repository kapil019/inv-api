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

    const DECIMAL_FIELDS = [
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
        'booking',
        'sales'
    ];

    public function translateDecimals() {
        foreach (self::DECIMAL_FIELDS as $field) {
            $this->{$field} = (float) $this->{$field};
        }
    }
    
}