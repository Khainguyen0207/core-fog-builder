<?php

namespace App\Http\Controllers\Abstract;

use App\Admin\Panels\SettingPanel;
use App\Facades\SettingHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(SettingPanel $panel)
    {
        return $panel->renderPanel();
    }

    public function update(Request $request)
    {
        $request = $request->except(['_token', '_method']);

        foreach ($request as $name => $value) {
            SettingHelper::set($name, $value ?? '');
        }

        return redirect()->back()->with('success', 'Settings have been updated');
    }
}
