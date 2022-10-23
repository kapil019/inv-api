<?php

namespace App\Http\Controllers\Api;

use \Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends ApiController
{

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
        $list = Setting::where('status', 1);
        $list->select('id', 'setting_for', 'default as value', 'input_type', 'validation');
        if (!empty($request->id)) {
            $list->where('id', $request->id);
        }
        if (!empty($request->settingFor)) {
            $list->where('setting_for', $request->settingFor);
        }
        $items = $list->orderBy('id', 'desc')->get();
        return $this->respond([
            'status' => $items ? true : false,
            'message' => $msg,
            'response' => $items
        ]);
    }

}
