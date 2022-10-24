<?php

namespace App\Http\Controllers\Api;

use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;

class CategoryController extends ApiController
{
    const FAILURE_MESSAGE = "Records not found. Please try again later";

    private $error = "Error while creating. Please try";
    private $rule = array(
        'name' => 'required',
        'companyId' => 'required',
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
        $list = Category::select(
            ['id', 'name', 'company_id', 'parent_id', 'status']
        )->where('status', 1);
        if (!empty($request->id)) {
            $list->where('id', $request->id);
        }
        if (!empty($request->companyId)) {
            $list->where('company_id', $request->companyId);
        }
        if (!empty($request->parentId)) {
            $list->where('parent_id', $request->parentId);
        }
        $categorys = $list->orderBy('id', 'desc')->simplePaginate();
        if ($categorys->isEmpty()) {
            $msg = self::FAILURE_MESSAGE;
        }
        foreach ($categorys as $category) {
            $category->_translate();
        }
        return $this->respond([
            'status' => $categorys ? true : false,
            'message' => $msg,
            'response' => $categorys
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
                $category = new Category();
                $category->name = $request->name;
                $category->company_id = $request->companyId;
                $category->parent_id = $request->parentId ?? 0;
                $category->status = $request->status ?? 1;
                $category->save();
                $msg = "Category created successfully";
            }
        } catch (\Exception  $e) {
            $this->error([__FILE__, __LINE__, __FUNCTION__, $e->getMessage()]);
            $msg = $this->error;
        }
        return $this->respond([
            'status' => ($category) ? true : false,
            'message' => $msg,
            'response' => $data
        ]);
    }

    public function update($id, Request $request) {
        $data = null;
        $msg = $this->error;
        try {
            $category = Category::findOrFail($id);
            $validator = Validator::make($request->all(), $this->rule, $this->ruleMessage);
            if ($validator->fails()) {
                return $this->respondValidationError($this->ruleMessage, $validator->errors());
            } else {
                if (!empty($request->name)) {
                    $category->name = $request->name;
                }
                if (isset($request->status)) {
                    $category->status = $request->status;
                }
                $category->save();
                $msg = "Category updated successfully";
            }
        } catch (\Exception  $e) {
            $this->error([__FILE__, __LINE__, __FUNCTION__, $e->getMessage()]);
            $msg = self::FAILURE_MESSAGE;
        }
        return $this->respond([
            'status' => ($category) ? true : false,
            'message' => $msg,
            'response' => $data
        ]);
    }

    public function delete($id) {
        $data = false;
        try {
            $data = Category::findOrFail($id)->delete();
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
