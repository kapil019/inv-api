<?php

namespace App\Models;

class Booking extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    protected $table = 'order';

    public $timestamps = true;
    
}