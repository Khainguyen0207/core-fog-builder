<?php

namespace Modules\Users\Http\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Users\Admin\Forms\UserForm;
use Modules\Users\Admin\Tables\UserTable;
use Modules\Users\Http\Requests\UserRequest;

class UserController extends Controller
{
    public function index(UserTable $table)
    {
        return $table->renderTable();
    }

    public function create()
    {
        return UserForm::make()->renderForm();
    }

    public function store(UserRequest $request)
    {
        try {
            User::query()->create($request->validated());

            return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function update(UserRequest $request, User $user)
    {
        $data = $request->validated();

        if ($password = $data['password']) {
            $user->password = Hash::make($password);
        } else {
            unset($data['password']);
        }

        $user->fill($data);

        if ($user->isDirty()) {
            $user->save();
        }

        return redirect(request()->input('_previous_url') ?? route('admin.users.index'))->with('success', 'User updated successfully');
    }

    public function show(User $user)
    {
        if (auth()->user()->id === $user->getKey()) {
            abort(404);
        }

        return UserForm::make()->createWithModel($user)->renderForm();
    }

    public function destroy(Request $request, User $user)
    {
        if ($request->user()->id === $user->getKey()) {
            return response()->json([
                'error' => true,
                'data' => null,
                'message' => 'Cannot delete the current user.',
            ]);
        }

        $user->delete();

        return response()->json([
            'error' => false,
            'data' => null,
            'message' => 'Delete user successfully',
        ]);
    }
}
