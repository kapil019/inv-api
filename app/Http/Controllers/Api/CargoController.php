<?php

namespace App\Http\Controllers\Api;

use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Cargo;

class CargoController extends ApiController
{
    const FAILURE_MESSAGE = "Records not found. Please try again later";

    private $error = "Error while creating. Please try";
    private $rule = array(
        'name' => 'required',
        'email' => 'required',
        'phone' => 'required',
        'gstNumber' => 'required',
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
        $data = Cargo::select([
            'id',
            'name',
            'phone',
            'email',
            'gst_number as gstNumber',
            'status'
        ])->SimplePaginate($this->perPage);
        if ($data->isEmpty()) {
            $msg = self::FAILURE_MESSAGE;
        }
        return $this->respond([
            'status' => $data ? true : false,
            'message' => $msg,
            'response' => $data
        ]);
    }

    public function get($id) {
        $data = null;
        $msg = null;
        try {
            $data = Cargo::select([
                'id',
                'name',
                'phone',
                'email',
                'gst_number as gstNumber',
                'status'
            ])->findOrFail($id);
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
                $cargo = new Cargo();
                $cargo->name =  $request->name;
                $cargo->email =  $request->email;
                $cargo->phone =  $request->phone;
                $cargo->gst_number =  $request->gstNumber ?? "";
                $cargo->status =  $request->status ?? 1;
                $cargo->save();
                $msg = "Cargo created successfully";
            }
        } catch (\Exception  $e) {
            $this->error([__FILE__, __LINE__, __FUNCTION__, $e->getMessage()]);
            $msg = $this->error;
        }
        return $this->respond([
            'status' => ($cargo) ? true : false,
            'message' => $msg,
            'response' => $data
        ]);
    }

    public function update($id, Request $request) {
        $data = null;
        $msg = $this->error;
        try {
            $cargo = Cargo::findOrFail($id);
            $validator = Validator::make($request->all(), $this->rule, $this->ruleMessage);
            if ($validator->fails()) {
                return $this->respondValidationError($this->ruleMessage, $validator->errors());
            } else {
                if (!empty($request->name)) {
                    $cargo->name =  $request->name;
                }
                if (!empty($request->email)) {
                    $cargo->email =  $request->email;
                }
                if (!empty($request->phone)) {
                    $cargo->phone =  $request->phone;
                }
                if (!empty($request->gstNumber)) {
                    $cargo->gst_number =  $request->gstNumber;
                }
                if (isset($request->status)) {
                    $cargo->status =  $request->status;
                }
                $cargo->save();
                $msg = "Cargo updated successfully";
            }
        } catch (\Exception  $e) {
            $this->error([__FILE__, __LINE__, __FUNCTION__, $e->getMessage()]);
            $msg = self::FAILURE_MESSAGE;
        }
        return $this->respond([
            'status' => ($cargo) ? true : false,
            'message' => $msg,
            'response' => $data
        ]);
    }

    public function delete($id) {
        $data = false;
        try {
            $data = Cargo::findOrFail($id)->delete();
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
