<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Forms\CouponForm;
use App\Admin\Tables\CouponTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CouponRequest;
use App\Models\Coupon;

class CouponController extends Controller
{
    public function index(CouponTable $table)
    {
        return $table->renderTable();
    }

    public function create()
    {
        return CouponForm::make()->renderForm();
    }

    public function store(CouponRequest $request)
    {
        Coupon::query()->create($request->validated());

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon created successfully.');
    }

    public function show(Coupon $coupon)
    {
        return CouponForm::make()
            ->createWithModel($coupon)
            ->renderForm();
    }

    public function edit(Coupon $coupon)
    {
        return CouponForm::make()->createWithModel($coupon)->renderForm();
    }

    public function update(CouponRequest $request, Coupon $coupon)
    {
        $coupon->update($request->validated());

        return redirect(request()->input('_previous_url') ?? route('admin.coupons.index'))->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return response()->json([
            'error' => false,
            'data' => null,
            'message' => 'Coupon deleted successfully',
        ]);
    }
}
