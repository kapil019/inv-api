<?php

namespace App\Http\Controllers\Api;

use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Company;

class CompanyController extends ApiController
{
    const FAILURE_MESSAGE = "Records not found. Please try again later";

    private $error = "Error while creating. Please try";
    private $rule = [
        'name' => 'required',
        'email' => 'required',
        'phone' => 'required',
        'gstNumber' => 'required'
    ];
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
        $items = [];
        $list = Company::select(['id',
            'name', 'phone', 'email', 'gst_number as gstNumber',
            'address_line_1 as addressLine1', 'address_line_2 as addressLine2',
            'logo_path as logoPath', 'status']
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
                $company = new Company();
                $company->name = $request->name;
                $company->email = $request->email;
                $company->phone = $request->phone;
                $company->gst_number = $request->gstNumber;
                $company->logo_path = $request->logoPath ?? "";
                $company->address_line_1 = $request->addressLine1 ?? "";
                $company->address_line_2 = $request->addressLine2 ?? "";
                $company->status = $request->status ?? 1;
                $company->save();
                $msg = "Company created successfully";
            }
        } catch (\Exception  $e) {
            $this->error([__FILE__, __LINE__, __FUNCTION__, $e->getMessage()]);
            $msg = $this->error;
        }
        return $this->respond([
            'status' => ($company) ? true : false,
            'message' => $msg,
            'response' => $data
        ]);
    }

    public function update($id, Request $request) {
        $data = null;
        $msg = $this->error;
        try {
            $company = Company::findOrFail($id);
            $validator = Validator::make($request->all(), $this->rule, $this->ruleMessage);
            if ($validator->fails()) {
                return $this->respondValidationError($this->ruleMessage, $validator->errors());
            } else {
                if (!empty($request->name)) {
                    $company->name = $request->name;
                }
                if (!empty($request->email)) {
                    $company->email = $request->email;
                }
                if (!empty($request->phone)) {
                    $company->phone = $request->phone;
                }
                if (!empty($request->gstNumber)) {
                    $company->gst_number = $request->gstNumber;
                }
                if (!empty($request->logoPath)) {
                    $company->logo_path = $request->logoPath;
                }
                if (!empty($request->addressLine1)) {
                    $company->address_line_1 = $request->addressLine1;
                }
                if (!empty($request->addressLine2)) {
                    $company->address_line_2 = $request->addressLine2;
                }
                if (isset($request->status)) {
                    $company->status = $request->status;
                }
                $company->save();
                $msg = "Company updated successfully";
            }
        } catch (\Exception  $e) {
            $this->error([__FILE__, __LINE__, __FUNCTION__, $e->getMessage()]);
            $msg = self::FAILURE_MESSAGE;
        }
        return $this->respond([
            'status' => ($company) ? true : false,
            'message' => $msg,
            'response' => $data
        ]);
    }

    public function delete($id) {
        $data = false;
        try {
            $data = Company::findOrFail($id)->delete();
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
