<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Api\ApiController;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use App\Models\OrderItem;

class OrderItemController extends ApiController
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
        $orders = [];
        try {
            if (!in_array($request->type, Order::TYPES)) {
                throw new \InvalidArgumentException('Type is not valid');
            }
            $list = OrderItem::where('orders.status', 1)->where('type', $request->type);
            if (!empty($request->get('createdFrom'))) {
                $list->where('orders.created_at', '>=', $request->get('createdFrom'));
            }
            if (!empty($request->get('createdTo'))) {
                $list->where('orders.created_at', '<=', $request->get('createdTo'));
            }
            if (!empty($request->get('priceFrom'))) {
                $list->where('orders.total_amount', '>=', $request->get('priceFrom'));
            }
            if (!empty($request->get('priceTo'))) {
                $list->where('orders.total_amount', '<=', $request->get('priceTo'));
            }
            if (!empty($request->get('companyId'))) {
                $list->where('orders.company_id', '=', $request->get('companyId'));
            }
            if (!empty($request->get('orderNumber'))) {
                $list->where('orders.order_number', '=', $request->get('orderNumber'));
            }
            $list->select(
                'orders.company_id as companyId',
                'orders.order_number as orderNumber',
                'orders.payment_status as paymentStatus',
                'orders.type as type',
                'orders.parent_id as parentId',
                'orders.state as state',
                'orders.customer_id as customerId',
                'orders.shipping_amount as shippingAmount',
                'orders.packing_amount as packingAmount',
                'orders.forward_amount as forwardAmount',
                'orders.printing_amount as printingAmount',
                'orders.discount_amount as discountAmount',
                'orders.grand_total as grandTotal',
                'orders.subtotal as subtotal',
                'orders.tax_amount as taxAmount',
                'orders.total_amount as totalAmount',
                'orders.total_paid as totalPaid',
                'orders.pending_amount as pendingAmount',
                'orders.invoice as invoice',
                'orders.invoice_number as invoiceNumber',
                'orders.invoice_url as invoiceUrl',
                'orders.remark as remark',
                'orders.status',
            );
            $orders = $list->orderBy('orders.id', 'desc')->SimplePaginate($this->perPage);
            foreach ($orders as $order) {
                $order->translateDecimals();
            }
        } catch (\InvalidArgumentException $e) {
            $this->error([__FILE__, __LINE__, __FUNCTION__, $e->getMessage()]);
            $msg = $e->getMessage();
        } catch (\Exception $e) {
            $this->error([__FILE__, __LINE__, __FUNCTION__, $e->getMessage()]);
            $msg = self::FAILURE_MESSAGE;
        }
        return $this->respond([
            'status' => $orders ? true : false,
            'message' => $msg,
            'response' => $orders
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
                $order = new OrderItem();
                $order->name =  $request->name;
                $order->status =  $request->status ?? 1;
                $order->save();
                $msg = "Order created successfully";
            }
        } catch (\Exception  $e) {
            $this->error([__FILE__, __LINE__, __FUNCTION__, $e->getMessage()]);
            $msg = $this->error;
        }
        return $this->respond([
            'status' => ($order) ? true : false,
            'message' => $msg,
            'response' => $data
        ]);
    }

    public function update($id, Request $request) {
        $data = null;
        $msg = $this->error;
        try {
            $order = OrderItem::findOrFail($id);
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
            $this->error([__FILE__, __LINE__, __FUNCTION__, $e->getMessage()]);
            $msg = self::FAILURE_MESSAGE;
        }
        return $this->respond([
            'status' => ($order) ? true : false,
            'message' => $msg,
            'response' => $data
        ]);
    }

    public function delete($id) {
        $data = false;
        try {
            $data = OrderItem::findOrFail($id)->delete();
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
