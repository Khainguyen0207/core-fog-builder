<?php

namespace Modules\Settings\Http\Admin\Controllers;

use App\Plugins\PluginManager;
use App\Plugins\PluginStateException;
use App\Plugins\PluginStatus;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Settings\Admin\Panels\Forms\InformationSystemForm;
use Modules\Settings\Admin\Panels\Forms\SePayPanelForm;
use Modules\Settings\Admin\Panels\Forms\TelegramPanelForm;
use Modules\Settings\Admin\Panels\Forms\WorkTimeForm;
use Modules\Settings\Http\Controllers\Abstract\SettingController as Controller;

class SettingController extends Controller
{
    public function sePay()
    {
        return SePayPanelForm::make()->renderForm();
    }

    public function workTime()
    {
        return WorkTimeForm::make()->renderForm();
    }

    public function informationSystem()
    {
        return InformationSystemForm::make()->renderForm();
    }

    public function telegram()
    {
        return TelegramPanelForm::make()->renderForm();
    }

    public function plugins(PluginManager $plugins): View
    {
        return view('settings::admin.pages.settings.plugins', [
            'statuses' => array_values(array_filter(
                $plugins->statuses(),
                fn (PluginStatus $status): bool => ! $status->definition->core,
            )),
        ]);
    }

    public function updatePlugins(Request $request, PluginManager $plugins): RedirectResponse
    {
        $validated = $request->validate([
            'enabled_plugins' => ['nullable', 'array'],
            'enabled_plugins.*' => ['string', 'distinct'],
        ]);

        try {
            $plugins->replaceEnabledPackages($validated['enabled_plugins'] ?? []);
        } catch (PluginStateException $exception) {
            return back()->withErrors(['enabled_plugins' => $exception->getMessage()]);
        }

        return back()->with('success', 'Plugin settings have been updated');
    }
}
