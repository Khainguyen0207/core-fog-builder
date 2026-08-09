<?php

namespace Modules\Settings\Http\Controllers\Abstract;

use App\Facades\SettingHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Settings\Admin\Panels\SettingPanel;

class SettingController extends Controller
{
    public function index(SettingPanel $panel)
    {
        $panel->setup();

        return view('settings::admin.pages.settings.index', [
            'panelSection' => $panel,
            'title' => $panel->getNameTable() ?? 'Example App',
        ]);
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
