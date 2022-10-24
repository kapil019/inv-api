<?php

namespace App\Models;

class Product extends Model
{

    protected $attributeList = [];
    protected $customFields = [];

    protected $appends = ['attributeList', 'customFields'];

    public $stringFields = ['mcWeight', 'mc_weight'];

    public function setAttributeListAttribute($data)
    {
        $this->attributeList[] = $data;
    }

    public function getAttributeListAttribute()
    {
        return $this->attributeList;
    }

    public function setCustomFieldsAttribute($data)
    {
        $this->customFields[] = $data;
    }

    public function getCustomFieldsAttribute()
    {
        return $this->customFields;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'status',
    ];

    protected $table = 'product';

    const CREATED_AT = 'EntryDate';
    const UPDATED_AT = 'UpdateDate';
}
