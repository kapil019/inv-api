<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    use HasFactory;

    public $integetFields = [];

    public $stringFields = [];

    public $decimalFields = [];

    protected function isInteger($key) {
        return in_array($key, $this->integetFields);
    }

    protected function isString($key) {
        return in_array($key, $this->stringFields);
    }

    protected function isDecimal($key) {
        return in_array($key, $this->decimalFields);
    }

    public function _translate() {
        $fields = array_keys($this->getAttributes());
        foreach ($fields as $field) {
            // Type cast
            if ($this->isInteger($field)) {
                $this->{$field} = (int) $this->{$field};
            } elseif ($this->isString($field)) {
                $this->{$field} = (string) $this->{$field};
            } elseif ($this->isDecimal($field)) {
                $this->{$field} = (float) $this->{$field};
            }
            // translate
            if (isset($this->{$field})) {
                $key = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $field))));
                if ($key != $field) {
                    $this->{$key} = $this->{$field};
                    unset($this->{$field});
                }
            } elseif (is_null($this->{$field})) {
                // $this->{$field} = "";
            }
        }
    }
}
