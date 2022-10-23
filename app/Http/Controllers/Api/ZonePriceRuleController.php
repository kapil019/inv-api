<?php

namespace App\Http\Controllers\Api;

use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ZonePriceRule;

class ZonePriceRuleController extends ApiController
{
    const FAILURE_MESSAGE = "Records not found. Please try again later";

    private $error = "Error while creating. Please try";
    private $rule = array(
        'rule_name' => 'required',
        'zone_id' => 'required',
        'discount_type' => 'required',
        'discount' => 'required'
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
        $data = ZonePriceRule::SimplePaginate($this->perPage);
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
            $data = ZonePriceRule::findOrFail($id);
            $msg = null;
        } catch (\Exception  $e) {
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
                $rule = new ZonePriceRule();
                $rule->rule_name =  $request->rule_name;
                $rule->zone_id =  $request->zone_id;
                $rule->category_id =  $request->category_id ?? 0;
                $rule->product_id =  $request->product_id ?? 0;
                $rule->product_variant_id =  $request->zone_id ?? 0;
                $rule->discount_type =  $request->discount_type ?? 'F';
                $rule->discount =  $request->discount ?? 0;
                $rule->status =  $request->status ?? 1;
                $rule->save();
                $msg = "Price rule created successfully";
            }
        } catch (\Exception  $e) {
            print_r($e->getMessage()); die;
            $msg = $this->error;
        }
        return $this->respond([
            'status' => ($rule) ? true : false,
            'message' => $msg,
            'response' => $data
        ]);
    }

    public function update($id, Request $request) {
        $data = null;
        $msg = $this->error;
        try {
            $rule = ZonePriceRule::findOrFail($id);
            $validator = Validator::make($request->all(), $this->rule, $this->ruleMessage);
            if ($validator->fails()) {
                return $this->respondValidationError($this->ruleMessage, $validator->errors());
            } else {
                if (!empty($request->rule_name)) {
                    $rule->rule_name =  $request->rule_name;
                }
                if (isset($request->zone_id)) {
                    $rule->zone_id =  $request->zone_id;
                }
                if (isset($request->category_id)) {
                    $rule->category_id =  $request->category_id;
                }
                if (isset($request->product_id)) {
                    $rule->product_id =  $request->product_id;
                }
                if (isset($request->product_variant_id)) {
                    $rule->product_variant_id =  $request->product_variant_id;
                }
                if (isset($request->discount_type)) {
                    $rule->discount_type =  $request->discount_type;
                }
                if (isset($request->discount)) {
                    $rule->discount =  $request->discount;
                }
                if (isset($request->status)) {
                    $rule->status =  $request->status;
                }
                $rule->save();
                $msg = "Price rule updated successfully";
            }
        } catch (\Exception  $e) {
            $msg = self::FAILURE_MESSAGE;
        }
        return $this->respond([
            'status' => ($rule) ? true : false,
            'message' => $msg,
            'response' => $data
        ]);
    }

    public function delete($id) {
        $data = false;
        try {
            $data = ZonePriceRule::findOrFail($id)->delete();
            $msg = "Record deleted successfully";
        } catch (\Exception $e) {
            $msg = self::FAILURE_MESSAGE;
        }
        return $this->respond([
            'status' => ($data) ? true : false,
            'message' => $msg,
            'response' => $data
        ]);
    }

}
