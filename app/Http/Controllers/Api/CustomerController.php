<?php

namespace App\Http\Controllers\Api;

use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;

class CustomerController extends ApiController
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
        $list = Customer::select(['id',
            'name', 'phone', 'email', 'state_id as stateId',
            'city_id as cityId', 'address_line_1 as addressLine1',
            'address_line_2 as addressLine2', 'pincode', 'status']
        );
        if (!empty($request->id)) {
            $list->where('id', $request->id);
        }
        $items = $list->orderBy('id', 'desc')->simplePaginate();
        if ($items->isEmpty()) {
            $msg = self::FAILURE_MESSAGE;
        }
        foreach ($items as $item) {
            $item->_translate();
        }
        return $this->respond([
            'status' => $items ? true : false,
            'message' => $msg,
            'response' => $items
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
                $customer = new Customer();
                $customer->name =  $request->name;
                $customer->email =  $request->email;
                $customer->phone =  $request->phone;
                $customer->company_id =  $request->companyId;
                $customer->state_id =  $request->stateId ?? null;
                $customer->city_id =  $request->cityId ?? null;
                $customer->address_line_1 =  $request->addressLine1 ?? "";
                $customer->address_line_2 =  $request->addressLine2 ?? "";
                $customer->pincode =  $request->pincode ?? null;
                $customer->status =  $request->status ?? 1;
                $customer->save();
                $msg = "Customer created successfully";
            }
        } catch (\Exception  $e) {
            $this->error([__FILE__, __LINE__, __FUNCTION__, $e->getMessage()]);
            $msg = $this->error;
        }
        return $this->respond([
            'status' => ($customer) ? true : false,
            'message' => $msg,
            'response' => $data
        ]);
    }

    public function update($id, Request $request) {
        $data = null;
        $msg = $this->error;
        try {
            $customer = Customer::findOrFail($id);
            $validator = Validator::make($request->all(), $this->rule, $this->ruleMessage);
            if ($validator->fails()) {
                return $this->respondValidationError($this->ruleMessage, $validator->errors());
            } else {
                if (!empty($request->name)) {
                    $customer->name =  $request->name;
                }
                if (!empty($request->email)) {
                    $customer->email =  $request->email;
                }
                if (!empty($request->phone)) {
                    $customer->phone =  $request->phone;
                }
                if (!empty($request->stateId)) {
                    $customer->state_id =  $request->stateId;
                }
                if (!empty($request->cityId)) {
                    $customer->city_id =  $request->cityId;
                }
                if (!empty($request->addressLine1)) {
                    $customer->address_line_1 =  $request->addressLine1;
                }
                if (!empty($request->addressLine2)) {
                    $customer->address_line_2 =  $request->addressLine2;
                }
                if (!empty($request->pincode)) {
                    $customer->pincode =  $request->pincode;
                }
                if (isset($request->status)) {
                    $customer->status =  $request->status;
                }
                $customer->save();
                $msg = "Customer updated successfully";
            }
        } catch (\Exception  $e) {
            $this->error([__FILE__, __LINE__, __FUNCTION__, $e->getMessage()]);
            $msg = self::FAILURE_MESSAGE;
        }
        return $this->respond([
            'status' => ($customer) ? true : false,
            'message' => $msg,
            'response' => $data
        ]);
    }

    public function delete($id) {
        $data = false;
        try {
            $data = Customer::findOrFail($id)->delete();
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
