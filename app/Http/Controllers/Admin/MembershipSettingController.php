<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Forms\MembershipSettingForm;
use App\Admin\Tables\MembershipSettingTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MembershipSettingRequest;
use App\Models\MembershipSetting;

class MembershipSettingController extends Controller
{
    public function index(MembershipSettingTable $table)
    {
        return $table->renderTable();
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
        $membershipSetting->update($request->validated());

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
