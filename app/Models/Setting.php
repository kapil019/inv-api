<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;
    public $stringFields = ['validation', 'value'];
}
