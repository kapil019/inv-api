<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Zone;

class ZoneController extends ApiController
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
        $data = Zone::SimplePaginate($this->perPage);
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
            $data = Zone::findOrFail($id);
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
                $zone = new Zone();
                $zone->name =  $request->name;
                $zone->status =  $request->status ?? 1;
                $zone->save();
                $msg = "Zone created successfully";
            }
        } catch (\Exception  $e) {
            print_r($e->getMessage()); die;
            $msg = $this->error;
        }
        return $this->respond([
            'status' => ($zone) ? true : false,
            'message' => $msg,
            'respond' => $data
        ]);
    }

    public function update($id, Request $request) {
        $data = null;
        $msg = $this->error;
        try {
            $zone = Zone::findOrFail($id);
            $validator = Validator::make($request->all(), $this->rule, $this->ruleMessage);
            if ($validator->fails()) {
                return $this->respondValidationError($this->ruleMessage, $validator->errors());
            } else {
                if (!empty($request->name)) {
                    $zone->name =  $request->name;
                }
                if (isset($request->status)) {
                    $zone->status =  $request->status;
                }
                $zone->save();
                $msg = "Zone updated successfully";
            }
        } catch (\Exception  $e) {
            $msg = self::FAILURE_MESSAGE;
        }
        return $this->respond([
            'status' => ($zone) ? true : false,
            'message' => $msg,
            'respond' => $data
        ]);
    }

    public function delete($id) {
        $data = false;
        try {
            $data = Zone::findOrFail($id)->delete();
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
