<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','ParentId', 'CategoryName', 'EntryDate', 'UpdateDate', 'ActionBy', 'status',
    ];

    protected $table = 'category';

    const CREATED_AT = 'EntryDate';
    const UPDATED_AT = 'UpdateDate';
}
