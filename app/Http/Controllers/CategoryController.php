<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;

class CategoryController extends ApiController
{
    private $error = "Error while creating. Please try";
    private $rule = array(
        'CategoryName' => 'required|max:255',
        'ActionBy' => 'required',
        'Status' => 'required'
    );
    private $ruleMessage = [
        'categoryName' => "Category Name is required",
        'actionBy' => "ActionBy is required",
        'status' => "Status is required",
    ];
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function getAll() {
        $msg = "";
        $data = Category::SimplePaginate($this->perPage);
        if($data->isEmpty()) {
            $msg = "Records not found. Please try";
        }
        return $this->respond([
            'status' => $data ? true : false,
            'message' => $msg,
            'respond' => $data
        ]);
    }

    public function get($id) {
        $data = null;
        $msg = "Records not found. Please try";
        try {
            $data = Category::findOrFail($id);
            $msg = null;
        } catch (\Exception  $e) {
            $status = false;
            $msg = "Records not found. Please try";
        }
        return $this->respond([
            'status' => $data ? true : false,
            'message' => $msg,
            'respond' => $data
        ]);
    }

    public function create(Request $request) {
        $data = null;
        $msg = $this->error;
        try {
            $params = $this->requestToModel($request->all());
            $validator = Validator::make($params, $this->rule, $this->ruleMessage);
            if ($validator->fails()) {
                return $this->respondValidationError($this->ruleMessage, $validator->errors());
            } else {
                $data = Category::create($params);
                $msg = "Category created successfully";
            }
        } catch (\Exception  $e) {
            $msg = $this->error;
        }
        return $this->respond([
            'status' => ($data) ? true : false,
            'message' => $msg,
            'respond' => $data
        ]);
    }

    public function update($id, Request $request) {
        $data = null;
        $msg = $this->error;
        try {
            $resp = Category::findOrFail($id);
            $params = $this->requestToModel($request->all());
            $validator = Validator::make($params, $this->rule, $this->ruleMessage);
            if ($validator->fails()) {
                return $this->respondValidationError($this->ruleMessage, $validator->errors());
            } else {
                $resp->update($params);
                $data = Category::findOrFail($id);
                $msg = "Category updated successfully";
            }
        } catch (\Exception  $e) {
            $msg = "Records not found. Please try";
        }
        return $this->respond([
            'status' => ($data) ? true : false,
            'message' => $msg,
            'respond' => $data
        ]);
    }

    public function delete($id) {
        $data = false;
        try {
            $data = Category::findOrFail($id)->delete();
            $msg = "Record deleted successfully";
        } catch (\Exception $e) {
            $msg = "Records not found. Please try";
        }
        return $this->respond([
            'status' => ($data) ? true : false,
            'message' => $msg,
            'respond' => $data
        ]);
    }

    function requestToModel($params) {
        $res['CategoryName'] = $params['categoryName'] ?? "";
        $res['ParentId'] = $params['parentId'] ?? 0;
        $res['Status'] = $params['status'] ?? 1;
        $res['ActionBy'] = $params['actionBy'];
        return $res;
    }
}
