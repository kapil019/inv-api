<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;

class OrderController extends ApiController
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
        $list = Order::where('orders.Status', 1);
        if (!empty($request->get('createdFrom'))) {
            $list->where('orders.EntryDate', '>=', $request->get('createdFrom'));
        }
        if (!empty($request->get('createdTo'))) {
            $list->where('orders.EntryDate', '<=', $request->get('createdTo'));
        }
        if (!empty($request->get('priceFrom'))) {
            $list->where('orders.TotalAmount', '>=', $request->get('priceFrom'));
        }
        if (!empty($request->get('priceTo'))) {
            $list->where('orders.TotalAmount', '<=', $request->get('priceTo'));
        }
        if (!empty($request->get('companyId'))) {
            $list->where('orders.company_id', '=', $request->get('companyId'));
        }
        if (!empty($request->get('ordersId'))) {
            $list->where('orders.OrderId', '=', $request->get('ordersId'));
        }
        $list->select(
            'orders.OrderId',
            'orders.CustomerId',
            'orders.CompanyId',
            'orders.TotalQty',
            'orders.PendingQty',
            'orders.DeliviredQty',
            'orders.TotalVatAmount',
            'orders.TotalAmount',
            'orders.PaidAmount',
            'orders.PendingAmount',
            'orders.PackingAmount',
            'orders.ForwardAmount',
            'orders.PrintingAmount',
            'orders.ShippingAmount',
            'orders.PageSource',
            'orders.GodownName',
            'orders.EntryType',
            'orders.PaymentType',
            'orders.Remark',
            'orders.Invoice',
            'orders.OrderStatus',
            'orders.Status',
            'orders.EntryDate',
        );
        $data = $list->orderBy('orders.id', 'desc')->SimplePaginate($this->perPage);

        if ($data->isEmpty()) {
            $msg = self::FAILURE_MESSAGE;
        }
        return $this->respond([
            'status' => $data ? true : false,
            'message' => $msg,
            'respond' => $data
        ]);
    }

    public function get($id) {
        $data = null;
        $msg = null;
        try {
            $data = Order::findOrFail($id);
            $msg = null;
        } catch (\Exception  $e) {
            $msg = self::FAILURE_MESSAGE;
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
            $validator = Validator::make($request->all(), $this->rule, $this->ruleMessage);
            if ($validator->fails()) {
                return $this->respondValidationError($this->ruleMessage, $validator->errors());
            } else {
                $order = new Order();
                $order->name =  $request->name;
                $order->status =  $request->status ?? 1;
                $order->save();
                $msg = "Order created successfully";
            }
        } catch (\Exception  $e) {
            $msg = $this->error;
        }
        return $this->respond([
            'status' => ($order) ? true : false,
            'message' => $msg,
            'respond' => $data
        ]);
    }

    public function update($id, Request $request) {
        $data = null;
        $msg = $this->error;
        try {
            $order = Order::findOrFail($id);
            $validator = Validator::make($request->all(), $this->rule, $this->ruleMessage);
            if ($validator->fails()) {
                return $this->respondValidationError($this->ruleMessage, $validator->errors());
            } else {
                if (!empty($request->name)) {
                    $order->name =  $request->name;
                }
                if (isset($request->status)) {
                    $order->status =  $request->status;
                }
                $order->save();
                $msg = "Order updated successfully";
            }
        } catch (\Exception  $e) {
            $msg = self::FAILURE_MESSAGE;
        }
        return $this->respond([
            'status' => ($order) ? true : false,
            'message' => $msg,
            'respond' => $data
        ]);
    }

    public function delete($id) {
        $data = false;
        try {
            $data = Order::findOrFail($id)->delete();
            $msg = "Record deleted successfully";
        } catch (\Exception $e) {
            $msg = self::FAILURE_MESSAGE;
        }
        return $this->respond([
            'status' => ($data) ? true : false,
            'message' => $msg,
            'respond' => $data
        ]);
    }

}
