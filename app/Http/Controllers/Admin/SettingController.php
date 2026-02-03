<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            $group = 'general';
            if (str_starts_with($key, 'mail_'))
                $group = 'smtp';
            if (str_starts_with($key, 'api_'))
                $group = 'api';
            if (str_starts_with($key, 'module_'))
                $group = 'modules';

            Setting::set($key, $value, $group);
        }

        // Si les paramètres SMTP ont changé, on peut vider le cache de config
        if ($request->hasAny(['mail_host', 'mail_port', 'mail_username'])) {
            Artisan::call('config:clear');
        }

        return back()->with('success', 'Paramètres mis à jour avec succès.');
    }
}