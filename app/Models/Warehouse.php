<?php

namespace App\Models;

class Warehouse extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id'];

    protected $table = 'warehouse';

    public $timestamps = true;
}