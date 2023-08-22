<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return view('settings.edit');
    }

    public function store(Request $request)
    {
        $data = $request->except('_token');
        $data['date_system'] = $data['date_system'] ?? 0;
        // $data['date_system_value'] = $data['date_system'] ? $data['date_system_value'] : null;
        $data['date_system_value'] = $data['date_system'] ? date('Y-m-d H:i:s', strtotime($data['date_system_value'])) : null;
        foreach ($data as $key => $value) {
            $setting = Setting::firstOrCreate(['key' => $key]);
            $setting->value = $value;
            $setting->save();
        }

        return redirect()->route('settings.index');
    }
}
