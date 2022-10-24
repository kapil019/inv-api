<?php

namespace App\Http\Controllers\Api;

use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Payment;

class PaymentController extends ApiController
{
    const FAILURE_MESSAGE = "Records not found. Please try again later";

    private $error = "Error while creating. Please try";
    private $rule = array(
        'companyId' => 'required',
        'customerId' => 'required',
        'amount' => 'required',
        'orderId' => 'required',
        'paymentType' => 'required',
        'paymentStatus' => 'required',
        'isReconciled' => 'required',
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
        $list = Payment::select(['id','company_id', 'customer_id', 'order_id', 'payment_date',
            'amount', 'invoice', 'payment_type', 'payment_status', 'remark', 'is_reconciled']
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
                $payment = new Payment();
                $payment->amount =  $request->amount;
                $payment->invoice =  $request->invoice ?? 'N';
                $payment->remark =  $request->remark ?? "";
                $payment->company_id =  $request->companyId;
                $payment->customer_id =  $request->customerId;
                $payment->order_id=  $request->orderId;
                $payment->payment_type =  $request->paymentType ?? "";
                $payment->payment_status =  $request->paymentStatus ?? "";
                $payment->is_reconciled =  $request->isReconciled ?? 0;
                $payment->payment_date = date("Y-m-d");
                $payment->save();
                $msg = "Payment created successfully";
            }
        } catch (\Exception  $e) {
            $this->error([__FILE__, __LINE__, __FUNCTION__, $e->getMessage()]);
            $msg = $this->error;
        }
        return $this->respond([
            'status' => ($payment) ? true : false,
            'message' => $msg,
            'response' => $data
        ]);
    }

    public function update($id, Request $request) {
        $data = null;
        $msg = $this->error;
        try {
            $payment = Payment::findOrFail($id);
            $validator = Validator::make($request->all(), $this->rule, $this->ruleMessage);
            if ($validator->fails()) {
                return $this->respondValidationError($this->ruleMessage, $validator->errors());
            } else {
                if (!empty($request->amount)) {
                    $payment->amount =  $request->amount;
                }
                if (!empty($request->invoice)) {
                    $payment->invoice =  $request->invoice;
                }
                if (!empty($request->remark)) {
                    $payment->remark =  $request->remark;
                }
                if (!empty($request->paymentStatus)) {
                    $payment->payment_status =  $request->paymentStatus;
                }
                if (!empty($request->isReconciled)) {
                    $payment->is_reconciled =  $request->isReconciled;
                }
                $payment->save();
                $msg = "Payment updated successfully";
            }
        } catch (\Exception  $e) {
            $this->error([__FILE__, __LINE__, __FUNCTION__, $e->getMessage()]);
            $msg = self::FAILURE_MESSAGE;
        }
        return $this->respond([
            'status' => ($payment) ? true : false,
            'message' => $msg,
            'response' => $data
        ]);
    }

    public function delete($id) {
        $data = false;
        try {
            $data = Payment::findOrFail($id)->delete();
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
