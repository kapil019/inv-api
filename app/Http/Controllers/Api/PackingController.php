<?php

namespace App\Http\Controllers\Api;

use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Packing;

class PackingController extends ApiController
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
        $msg = null;
        $packings = [];
        $list = Packing::select(['id', 'name', 'status']);
        if (!empty($request->id)) {
            $list->where('id', $request->id);
        }
        $packings = $list->orderBy('id', 'desc')->simplePaginate();
        if ($packings->isEmpty()) {
            $msg = self::FAILURE_MESSAGE;
        }
        foreach ($packings as $cargo) {
            $cargo->_translate();
        }
        return $this->respond([
            'status' => $packings ? true : false,
            'message' => $msg,
            'response' => $packings
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
                $packing = new Packing();
                $packing->name = $request->name;
                $packing->status = $request->status ?? 1;
                $packing->save();
                $msg = "Packing created successfully";
            }
        } catch (\Exception  $e) {
            $this->error([__FILE__, __LINE__, __FUNCTION__, $e->getMessage()]);
            $msg = $this->error;
        }
        return $this->respond([
            'status' => ($packing) ? true : false,
            'message' => $msg,
            'response' => $data
        ]);
    }

    public function update($id, Request $request) {
        $data = null;
        $msg = $this->error;
        try {
            $packing = Packing::findOrFail($id);
            $validator = Validator::make($request->all(), $this->rule, $this->ruleMessage);
            if ($validator->fails()) {
                return $this->respondValidationError($this->ruleMessage, $validator->errors());
            } else {
                if (!empty($request->name)) {
                    $packing->name = $request->name;
                }
                if (isset($request->status)) {
                    $packing->status = $request->status;
                }
                $packing->save();
                $msg = "Packing updated successfully";
            }
        } catch (\Exception  $e) {
            $this->error([__FILE__, __LINE__, __FUNCTION__, $e->getMessage()]);
            $msg = self::FAILURE_MESSAGE;
        }
        return $this->respond([
            'status' => ($packing) ? true : false,
            'message' => $msg,
            'response' => $data
        ]);
    }

    public function delete($id) {
        $data = false;
        try {
            $data = Packing::findOrFail($id)->delete();
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
