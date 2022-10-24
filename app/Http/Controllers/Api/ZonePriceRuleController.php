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
        'ruleName' => 'required',
        'zoneId' => 'required',
        'discountType' => 'required',
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
        $rules = [];
        $list = ZonePriceRule::select([
            'id', 'rule_name', 'zone_id', 'discount_type', 'discount',
            'category_id', 'status', 'product_id', 'product_variant_id'
        ]);
        if (!empty($request->id)) {
            $list->where('id', $request->id);
        }
        if (!empty($request->zoneId)) {
            $list->where('zone_id', $request->zoneId);
        }
        $rules = $list->orderBy('id', 'desc')->simplePaginate();
        if ($rules->isEmpty()) {
            $msg = self::FAILURE_MESSAGE;
        }
        foreach ($rules as $rule) {
            $rule->_translate();
        }
        return $this->respond([
            'status' => $rules ? true : false,
            'message' => $msg,
            'response' => $rules
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
                $rule->rule_name = $request->ruleName;
                $rule->zone_id = $request->zoneId;
                $rule->category_id = $request->categoryId ?? 0;
                $rule->product_id = $request->productId ?? 0;
                $rule->product_variant_id = $request->productVariantId ?? 0;
                $rule->discount_type = $request->discountType ?? 'F';
                $rule->discount = $request->discount ?? 0;
                $rule->status = $request->status ?? 1;
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
                if (!empty($request->ruleName)) {
                    $rule->rule_name = $request->ruleName;
                }
                if (isset($request->zoneId)) {
                    $rule->zone_id = $request->zoneId;
                }
                if (isset($request->categoryId)) {
                    $rule->category_id = $request->categoryId;
                }
                if (isset($request->productId)) {
                    $rule->product_id = $request->productId;
                }
                if (isset($request->productVariantId)) {
                    $rule->product_variant_id = $request->productVariantId;
                }
                if (isset($request->discountType)) {
                    $rule->discount_type = $request->discountType;
                }
                if (isset($request->discount)) {
                    $rule->discount = $request->discount;
                }
                if (isset($request->status)) {
                    $rule->status = $request->status;
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
