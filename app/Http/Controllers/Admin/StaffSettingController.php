<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Panels\Forms\ActiveStaffForm;
use App\Facades\SettingHelper;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Staff;
use Illuminate\Http\Request;

class StaffSettingController extends Controller
{
    public function activeStaff()
    {
        return ActiveStaffForm::make()->renderForm();
    }

    public function updateActiveStaff(Request $request)
    {
        $request->validate([
            'max_active_staff' => 'required|integer|min:1',
        ]);

        $countActiveStaff = Staff::query()
            ->where('is_active', true)
            ->count();

        if ($countActiveStaff < (int) $request->max_active_staff) {
            return redirect()->route('admin.settings.active-staff.index')
                ->with('error', 'Số lượng nhân viên active hiện tại là ' . $countActiveStaff . ', không thể cập nhật với số lượng lớn hơn.');
        }

        SettingHelper::set('max_active_staff', $request->max_active_staff);

        return redirect()->route('admin.settings.active-staff.index')
            ->with('success', 'Cập nhật số lượng nhân viên active thành công!');
    }
}
