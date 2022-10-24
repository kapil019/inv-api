<?php

namespace App\Http\Controllers\Api;

use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\AttributeValue;

class AttributeValueController extends ApiController
{
    const FAILURE_MESSAGE = "Records not found. Please try again later";

    private $error = "Error while creating. Please try";
    private $rule = array(
        'name' => 'required',
    );
    private $ruleMessage = [];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function getAll(Request $request)
    {
        $msg = null; $values = [];
        try {
            if (empty($request->attributeId)) {
                throw new \InvalidArgumentException('Attribute id is required');
            }
            $list = AttributeValue::select(['id', 'attribute_id', 'value']);
            if (!empty($request->id)) {
                $list->where('id', $request->id);
            }
            $list->where('attribute_id', $request->attributeId);
            $values = $list->orderBy('id', 'desc')->get();
            if ($values->isEmpty()) {
                $msg = self::FAILURE_MESSAGE;
            }
            foreach ($values as $attribute) {
                $attribute->_translate();
            }
        } catch (\InvalidArgumentException $e) {
            $this->error([__FILE__, __LINE__, __FUNCTION__, $e->getMessage()]);
            $msg = $e->getMessage();
        } catch (\Exception  $e) {
            $this->error([__FILE__, __LINE__, __FUNCTION__, $e->getMessage()]);
            $msg = self::FAILURE_MESSAGE;
        }
        return $this->respond([
            'status' => $values ? true : false,
            'message' => $msg,
            'response' => $values
        ]);
    }

    public function create(Request $request) {
        $data = null;
        $msg = $this->error;
        try {
            $validator = Validator::make($request->all(), $this->rule, $this->ruleMessage);
            if ($validator->fails()) {
                return $this->respondValidationError($this->ruleMessage, $validator->errors());
            } else {
                $attributeValue = new AttributeValue();
                $attributeValue->name = $request->name;
                $attributeValue->status = $request->status ?? 1;
                $attributeValue->save();
                $msg = "AttributeValue created successfully";
            }
        } catch (\Exception  $e) {
            $this->error([__FILE__, __LINE__, __FUNCTION__, $e->getMessage()]);
            $msg = $this->error;
        }
        return $this->respond([
            'status' => ($attributeValue) ? true : false,
            'message' => $msg,
            'response' => $data
        ]);
    }

    public function update($id, Request $request) {
        $data = null;
        $msg = $this->error;
        try {
            $attributeValue = AttributeValue::findOrFail($id);
            $validator = Validator::make($request->all(), $this->rule, $this->ruleMessage);
            if ($validator->fails()) {
                return $this->respondValidationError($this->ruleMessage, $validator->errors());
            } else {
                if (!empty($request->name)) {
                    $attributeValue->name = $request->name;
                }
                if (isset($request->status)) {
                    $attributeValue->status = $request->status;
                }
                $attributeValue->save();
                $msg = "AttributeValue updated successfully";
            }
        } catch (\Exception  $e) {
            $this->error([__FILE__, __LINE__, __FUNCTION__, $e->getMessage()]);
            $msg = self::FAILURE_MESSAGE;
        }
        return $this->respond([
            'status' => ($attributeValue) ? true : false,
            'message' => $msg,
            'response' => $data
        ]);
    }

    public function delete($id) {
        $data = false;
        try {
            $data = AttributeValue::findOrFail($id)->delete();
            $msg = "Record deleted successfully";
        } catch (\Exception $e) {
            $this->error([__FILE__, __LINE__, __FUNCTION__, $e->getMessage()]);
            $msg = self::FAILURE_MESSAGE;
        }
        return $this->respond([
            'status' => ($data) ? true : false,
            'message' => $msg,
            'response' => $data
        ]);
    }

}
