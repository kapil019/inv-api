<?php

namespace App\Http\Controllers\Api;

use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Warehouse;

class WarehouseController extends ApiController
{
    const FAILURE_MESSAGE = "Records not found. Please try again later";

    private $error = "Error while creating. Please try";
    private $rule = array(
        'name' => 'required',
        'email' => 'required',
        'phone' => 'required',
        'addressLine1' => 'required',
        'companyId' => 'required'
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
        $data = Warehouse::select([
            'id',
            'name',
            'phone',
            'email',
            'address_line_1 as addressLine1',
            'address_line_2 as addressLine2',
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
            $data = Warehouse::select([
                'id',
                'name',
                'phone',
                'email',
                'address_line_1 as addressLine1',
                'address_line_2 as addressLine2',
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
                $warehouse = new Warehouse();
                $warehouse->name =  $request->name;
                $warehouse->email =  $request->email;
                $warehouse->phone =  $request->phone;
                $warehouse->company_id =  $request->companyId;
                $warehouse->address_line_1 =  $request->addressLine1 ?? "";
                $warehouse->address_line_2 =  $request->addressLine2 ?? "";
                $warehouse->status =  $request->status ?? 1;
                $warehouse->save();
                $msg = "Warehouse created successfully";
            }
        } catch (\Exception  $e) {
            $this->error([__FILE__, __LINE__, __FUNCTION__, $e->getMessage()]);
            $msg = $this->error;
        }
        return $this->respond([
            'status' => ($warehouse) ? true : false,
            'message' => $msg,
            'response' => $data
        ]);
    }

    public function update($id, Request $request) {
        $data = null;
        $msg = $this->error;
        try {
            $warehouse = Warehouse::findOrFail($id);
            $validator = Validator::make($request->all(), $this->rule, $this->ruleMessage);
            if ($validator->fails()) {
                return $this->respondValidationError($this->ruleMessage, $validator->errors());
            } else {
                if (!empty($request->name)) {
                    $warehouse->name =  $request->name;
                }
                if (!empty($request->email)) {
                    $warehouse->email =  $request->email;
                }
                if (!empty($request->phone)) {
                    $warehouse->phone =  $request->phone;
                }
                if (!empty($request->addressLine1)) {
                    $warehouse->address_line_1 =  $request->addressLine1;
                }
                if (!empty($request->addressLine2)) {
                    $warehouse->address_line_2 =  $request->addressLine2;
                }
                if (isset($request->status)) {
                    $warehouse->status =  $request->status;
                }
                $warehouse->save();
                $msg = "Warehouse updated successfully";
            }
        } catch (\Exception  $e) {
            $this->error([__FILE__, __LINE__, __FUNCTION__, $e->getMessage()]);
            $msg = self::FAILURE_MESSAGE;
        }
        return $this->respond([
            'status' => ($warehouse) ? true : false,
            'message' => $msg,
            'response' => $data
        ]);
    }

    public function delete($id) {
        $data = false;
        try {
            $data = Warehouse::findOrFail($id)->delete();
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
