<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'app_name' => Setting::get('app_name', 'Lyline'),
            'default_language' => Setting::get('default_language', 'en'),
            'timezone' => Setting::get('timezone', 'Asia/Jakarta'),
            'date_format' => Setting::get('date_format', 'd/m/Y'),
            'currency' => Setting::get('currency', 'IDR'),
        ];

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'default_language' => 'required|in:en,id',
            'timezone' => 'required|string',
            'date_format' => 'required|string',
            'currency' => 'required|string|max:10',
        ]);

        Setting::set('app_name', $request->app_name);
        Setting::set('default_language', $request->default_language);
        Setting::set('timezone', $request->timezone);
        Setting::set('date_format', $request->date_format);
        Setting::set('currency', $request->currency);

        return redirect()->back()->with('success', __('Settings updated successfully.'));
    }
}
