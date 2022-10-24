<?php

namespace App\Http\Controllers\Api;

use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Attribute;

class AttributeController extends ApiController
{
    const FAILURE_MESSAGE = "Records not found. Please try again later";

    private $error = "Error while creating. Please try";
    private $rule = array(
        'name' => 'required',
        'companyId' => 'required',
        'categoryId' => 'required',
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
        $msg = null;
        $list = Attribute::select(
            ['id', 'name', 'company_id', 'category_id', 'status']
        )->where('status', 1);
        if (!empty($request->id)) {
            $list->where('id', $request->id);
        }
        if (!empty($request->companyId)) {
            $list->where('company_id', $request->companyId);
        }
        if (!empty($request->categoryId)) {
            $list->where('category_id', $request->categoryId);
        }
        $attributes = $list->orderBy('id', 'desc')->simplePaginate();
        if ($attributes->isEmpty()) {
            $msg = self::FAILURE_MESSAGE;
        }
        foreach ($attributes as $attribute) {
            $attribute->_translate();
        }
        return $this->respond([
            'status' => $attributes ? true : false,
            'message' => $msg,
            'response' => $attributes
        ]);
    }

    public function get($id) {
        $data = null;
        $msg = null;
        try {
            $data = Attribute::select(['id', 'name', 'status'])->findOrFail($id);
            $msg = null;
        } catch (\Exception  $e) {
            $this->error([__FILE__, __LINE__, __FUNCTION__, $e->getMessage()]);
            $msg = self::FAILURE_MESSAGE;
        }
        return $this->respond([
            'status' => $data ? true : false,
            'message' => $msg,
            'response' => $data
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
                $attribute = new Attribute();
                $attribute->name = $request->name;
                $attribute->company_id = $request->companyId;
                $attribute->category_id = $request->categoryId;
                $attribute->status = $request->status ?? 1;
                $attribute->save();
                $msg = "Attribute created successfully";
            }
        } catch (\Exception  $e) {
            $this->error([__FILE__, __LINE__, __FUNCTION__, $e->getMessage()]);
            $msg = $this->error;
        }
        return $this->respond([
            'status' => ($attribute) ? true : false,
            'message' => $msg,
            'response' => $data
        ]);
    }

    public function update($id, Request $request) {
        $data = null;
        $msg = $this->error;
        try {
            $attribute = Attribute::findOrFail($id);
            $validator = Validator::make($request->all(), $this->rule, $this->ruleMessage);
            if ($validator->fails()) {
                return $this->respondValidationError($this->ruleMessage, $validator->errors());
            } else {
                if (!empty($request->name)) {
                    $attribute->name = $request->name;
                }
                if (isset($request->status)) {
                    $attribute->status = $request->status;
                }
                $attribute->save();
                $msg = "Attribute updated successfully";
            }
        } catch (\Exception  $e) {
            $this->error([__FILE__, __LINE__, __FUNCTION__, $e->getMessage()]);
            $msg = self::FAILURE_MESSAGE;
        }
        return $this->respond([
            'status' => ($attribute) ? true : false,
            'message' => $msg,
            'response' => $data
        ]);
    }

    public function delete($id) {
        $data = false;
        try {
            $data = Attribute::findOrFail($id)->delete();
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
