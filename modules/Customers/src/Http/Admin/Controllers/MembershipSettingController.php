<?php

namespace Modules\Customers\Http\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MembershipSetting;
use Modules\Customers\Admin\Forms\MembershipSettingForm;
use Modules\Customers\Admin\Tables\MembershipSettingTable;
use Modules\Customers\Http\Requests\MembershipSettingRequest;

class MembershipSettingController extends Controller
{
    public function index(MembershipSettingTable $table)
    {
        return $table->renderTable();
    }

    public function create()
    {
        return MembershipSettingForm::make()->renderForm();
    }

    public function store(MembershipSettingRequest $request)
    {
        MembershipSetting::query()->create($request->validated());

        return redirect()->route('admin.membership-settings.index')
            ->with('success', 'Membership setting created successfully.');
    }

    public function show(MembershipSetting $membershipSetting)
    {
        return MembershipSettingForm::make()->createWithModel($membershipSetting)->renderForm();
    }

    public function edit(MembershipSetting $membershipSetting)
    {
        return MembershipSettingForm::make()->createWithModel($membershipSetting)->renderForm();
    }

    public function update(MembershipSettingRequest $request, MembershipSetting $membershipSetting)
    {
        $membershipSetting->update($request->safe()->except('membership_code'));

        return redirect()->back()
            ->with('success', 'Membership setting updated successfully.');
    }

    public function destroy(MembershipSetting $membershipSetting)
    {
        $membershipSetting->delete();

        return response()->json([
            'error' => false,
            'data' => null,
            'message' => 'Membership setting deleted successfully',
        ]);
    }
}
