<?php

namespace App\Http\Controllers\Api;

use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class ProductController extends ApiController
{
    const FAILURE_MESSAGE = "Records not found. Please try again later";

    private $error = "Error while creating. Please try";
    private $rule = [
        'productName' => 'required|max:255',
        'actionBy' => 'required',
        'status' => 'required',
        'price' => 'required',
        'productId' => 'required',
        'listNo' => 'required',
        'hsnCode' => 'required'
    ];
    private $ruleMessage = [
        'product_name' => "Product Name is required",
        'actionBy' => "action_by is required",
        'status' => "status is required",
    ];
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function getAll(Request $request)
    {
        $msg = null;
        $products = [];
        // $this->authinticate('product', 'list');
        try {
            $list = Product::where('product.status', 1);
            $list->join('category', 'category.id', '=', 'product.category_id');
            if (!empty($request->get('createdFrom'))) {
                $list->where('product.created_at', '>=', $request->get('createdFrom'));
            }
            if (!empty($request->get('createdTo'))) {
                $list->where('product.created_at', '<=', $request->get('createdTo'));
            }
            if (!empty($request->get('priceFrom'))) {
                $list->where('product.price', '>=', $request->get('priceFrom'));
            }
            if (!empty($request->get('priceTo'))) {
                $list->where('product.price', '<=', $request->get('priceTo'));
            }
            if (!empty($request->get('category_id'))) {
                $list->where('product.category_id', '=', $request->get('category_id'));
            }
            if (!empty($request->get('productId'))) {
                $list->where('product.product_id', '=', $request->get('productId'));
            }
            if (!empty($request->get('listNo'))) {
                $list->where('product.list_no', '=', $request->get('listNo'));
            }
            $list->select(
                'product.product_id as product_id',
                'product.category_id as categoryId',
                'product.product_name as productName',
                'product.list_no as listNo',
                'product.model_no as modelNo',
                'product.packing',
                'product.packing_name as packingName',
                'product.indian_product as indialProduct',
                'product.packing_per_carton as packingPerCarton',
                'product.mc_dimensions as mcDimensions',
                'product.mc_weight as mcWeight',
                'product.pd_dimensions as pdDimensions',
                'product.pd_weight as pdWeight',
                'product.price',
                'product.ask_price as askPrice',
                'product.cost',
                'product.product_image as productImage',
                'product.product_type as productType',
                'product.tags',
                'product.hsn_code as hsnCode',
                'product.created_at as createdAt',
                'product.box',
                'product.tax_rate as taxRate',
                'product.status',
                'category.category_name as categoryName',
            );
            $products = $list->orderBy('product.id', 'desc')->simplePaginate($this->perPage);
            if ($products->isEmpty()) {
                $msg = self::FAILURE_MESSAGE;
            }
            foreach ($products as $product) {
                $product->_translate();
            }
        } catch (\Exception $e) {
            $this->error([__FILE__, __LINE__, __FUNCTION__, $e->getMessage()]);
            $msg = self::FAILURE_MESSAGE;
        }
        return $this->respond([
            'status' => $products ? true : false,
            'message' => $msg,
            'response' => $products
        ]);
    }

    public function get($id) {
        $product = null;
        $msg = null;
        try {
            $product = Product::join('category', 'category.id', '=', 'product.category_id')
            ->select(
                'product.product_id as productId',
                'product.category_id as categoryId',
                'product.product_name as productName',
                'product.list_no as listNo',
                'product.model_no as modelNo',
                'product.packing',
                'product.packing_name as packingName',
                'product.indian_product as indialProduct',
                'product.packing_per_carton as packingPerCarton',
                'product.mc_dimensions as mcDimensions',
                'product.mc_weight as mcWeight',
                'product.pd_dimensions as pdDimensions',
                'product.pd_weight as pdWeight',
                'product.price',
                'product.ask_price as askPrice',
                'product.cost',
                'product.product_image as productImage',
                'product.product_type as productType',
                'product.tags',
                'product.hsn_code as hsnCode',
                'product.created_at as createdAt',
                'product.box',
                'product.tax_rate as taxRate',
                'product.status',
                'category.category_name as categoryName',
            )
            ->findOrFail($id);
            $product->_translate();
            $attribute_data = DB::select(
                DB::raw("SELECT
                    a.id as attributeId, a.name as attributeName, pv.id as variantId,
                    av.value as attributeValue, av.id as attributeValueId
                    from product_variants pv
                    join product_attributes pa on pa.product_variants = pv.id
                    join attributes a on pa.attribute_id = a.id
                    join attribute_values av on pa.value = av.id
                    where product_id = {$id}")
            );
            $attributes = [];
            if (!empty($attribute_data)) {
                foreach ($attribute_data as $attribute) {
                    if (empty($attributes[$attribute->variantId])) {
                        $attributes[$attribute->variantId] = [
                            'variantId' => $attribute->variantId,
                            'data' => [],
                        ];
                    }
                    $attributes[$attribute->variantId]['data'][] = $attribute;
                }
                $product->setAttributeListAttribute(array_values($attributes));
            }
            $msg = null;
        } catch (\Exception  $e) {
            $this->error([__FILE__, __LINE__, __FUNCTION__, $e->getMessage()]);
            $msg = self::FAILURE_MESSAGE;
        }
        return $this->respond([
            'status' => $product ? true : false,
            'message' => $msg,
            'response' => $product
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
                $product = new Product();
                $product->product_name = $request->productName ?? "";
                $product->category_id = $request->category_id ?? 0;
                $product->list_no = $request->listNo ?? "";
                $product->model_no = $request->modelNo ?? "";
                $product->indian_product = $request->indianProduct ?? "";
                $product->price = $request->price;
                $product->ask_price = $request->askprice;
                $product->product_type = $request->productType ?? "";
                $product->status = $request->status ?? 1;
                $product->action_by = $request->actionBy;
                $product->packing_name = $request->tackingName ?? "";
                $product->product_image = $request->troductImage ?? "";
                $product->tax_rate = $request->tax_rate ?? "";
                $product->product_id = $request->productId;
                $product->packing = $request->packing ?? '';
                $product->mc_dimensions = $request->mcDimensions ?? '';
                $product->packingPerCarton = $request->packingPerCarton ?? '';
                $product->mc_weight = $request->mcWeight ?? '';
                $product->pd_dimensions = $request->pdDimensions ?? '';
                $product->pd_weight = $request->pdWeight ?? '';
                $product->cost = $request->cost ?? null;
                $product->tags = $request->tags ?? '';
                $product->hsn_code = $request->hsnCode;
                $product->box = $request->box ?? 0;
                $product->save();
                $msg = "Product created successfully";
            }
        } catch (\Exception  $e) {
            $this->error([__FILE__, __LINE__, __FUNCTION__, $e->getMessage()]);
            $msg = $this->error;
        }
        return $this->respond([
            'status' => ($product) ? true : false,
            'message' => $msg,
            'response' => $data
        ]);
    }

    public function update($id, Request $request) {
        $data = null;
        $msg = $this->error;
        $status = false;
        try {
            $product = Product::findOrFail($id);
            $validator = Validator::make($request->all(), $this->rule, $this->ruleMessage);
            if ($validator->fails()) {
                return $this->respondValidationError($this->ruleMessage, $validator->errors());
            } else {
                if (!empty($request->product_name)) {
                    $product->product_name = $request->product_name;
                }
                if (!empty($request->category_id)) {
                    $product->category_id = $request->category_id;
                }
                if (!empty($request->category_id)) {
                    $product->YoutubeLink = $request->youtubeLink;
                }
                if (!empty($request->category_id)) {
                    $product->list_no = $request->listNo;
                }
                if (!empty($request->category_id)) {
                    $product->model_no = $request->modelNo;
                }
                if (!empty($request->category_id)) {
                    $product->indian_product = $request->indianProduct;
                }
                if (!empty($request->category_id)) {
                    $product->price = $request->price;
                }
                if (!empty($request->askprice)) {
                    $product->ask_price = $request->askprice;
                }
                if (!empty($request->productType)) {
                    $product->product_type = $request->productType;
                }
                if (!empty($request->status)) {
                    $product->status = $request->status;
                }
                if (!empty($request->actionBy)) {
                    $product->action_by = $request->actionBy;
                }
                if (!empty($request->packingName)) {
                    $product->packing_name = $request->packingName;
                }
                if (!empty($request->productImage)) {
                    $product->product_image = $request->productImage;
                }
                if (!empty($request->tax_rate)) {
                    $product->tax_rate = $request->tax_rate;
                }
                if (!empty($request->packing)) {
                    $product->packing = $request->packing;
                }
                if (!empty($request->mcDimensions)) {
                    $product->mc_dimensions = $request->mcDimensions;
                }
                if (!empty($request->packingPerCarton)) {
                    $product->packingPerCarton = $request->packingPerCarton;
                }
                if (!empty($request->mcWeight)) {
                    $product->mc_weight = $request->mcWeight;
                }
                if (!empty($request->pdDimensions)) {
                    $product->pd_dimensions = $request->pdDimensions;
                }
                if (!empty($request->pdWeight)) {
                    $product->pd_weight = $request->pdWeight;
                }
                if (!empty($request->cost)) {
                    $product->cost = $request->cost;
                }
                if (!empty($request->tags)) {
                    $product->tags = $request->tags;
                }
                if (!empty($request->hsnCode)) {
                    $product->hsn_code = $request->hsnCode;
                }
                if (isset($request->box)) {
                    $product->box = $request->box;
                }
                $product->save();
                $msg = "Product updated successfully";
                $status = true;
            }
        } catch (\Exception  $e) {
            $this->error([__FILE__, __LINE__, __FUNCTION__, $e->getMessage()]);
            $msg = self::FAILURE_MESSAGE;
        }
        return $this->respond([
            'status' => $status,
            'message' => $msg,
            'response' => $data
        ]);
    }

    public function delete($id) {
        $data = false;
        try {
            $data = Product::findOrFail($id)->delete();
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
