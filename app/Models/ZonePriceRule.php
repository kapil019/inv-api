<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZonePriceRule extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    protected $table = 'zone_price_rule';

    public $timestamps = true;
    
}