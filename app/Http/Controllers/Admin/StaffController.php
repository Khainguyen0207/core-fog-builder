<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Forms\StaffForm;
use App\Admin\Tables\StaffTable;
use App\Enums\UserGroupRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StaffRequest;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StaffController extends Controller
{
    public function index(StaffTable $table)
    {
        return $table->renderTable();
    }

    public function create()
    {
        return StaffForm::make()->renderForm();
    }

    public function store(StaffRequest $request)
    {
        $data = $request->validated();

        if ($file = $request->file('avatar')) {
            $fileName = Str::random(20) . '.' . $file->getClientOriginalExtension();

            $fileName = $file->storeAs('avatars', $fileName, 'public');

            $data['avatar'] = $fileName;
        }

        $user = User::query()->create([
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'group_role' => UserGroupRoleEnum::STAFF,
            'status' => $request->input('is_active')
        ]);

        $data['user_id'] = $user->id;

        $staff = Staff::create($data);

        if ($request->has('service_ids')) {
            $staff->services()->sync($request->input('service_ids', []));
        }

        return redirect()->route('admin.staffs.index')
            ->with('success', 'Staff created successfully.');
    }

    public function show(Staff $staff)
    {
        $form = StaffForm::make()->createWithModel($staff);

        $form->getField('service_ids')->setValue(
            $staff->services->pluck('id', 'title')->toArray()
        );

        return $form->renderForm();
    }

    public function edit(Staff $staff)
    {
        return StaffForm::make()->createWithModel($staff)->renderForm();
    }

    public function update(StaffRequest $request, Staff $staff)
    {
        $data = $request->validated();

        if ($file = $request->file('avatar')) {
            $fileName = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $fileName = $file->storeAs('avatars', $fileName, 'public');

            if ($staff->avatar && Storage::disk('public')->exists($staff->avatar)) {
                Storage::disk('public')->delete($staff->avatar);
            }

            $data['avatar'] = $fileName;
        }

        $staff->update($data);

        $staff->services()->sync($request->input('service_ids', []));

        return redirect(request()->input('_previous_url') ?? route('admin.staffs.index'))->with('success', 'Staff updated successfully.');
    }

    public function destroy(Staff $staff)
    {
        $staff->delete();

        return response()->json([
            'error' => false,
            'data' => null,
            'message' => 'Staff deleted successfully',
        ]);
    }
}
