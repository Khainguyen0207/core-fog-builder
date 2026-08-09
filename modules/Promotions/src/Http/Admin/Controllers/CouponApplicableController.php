<?php

namespace Modules\Promotions\Http\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CouponApplicable;
use Modules\Promotions\Admin\Forms\CouponApplicableForm;
use Modules\Promotions\Admin\Tables\CouponApplicableTable;
use Modules\Promotions\Http\Requests\CouponApplicableRequest;

class CouponApplicableController extends Controller
{
    public function index(CouponApplicableTable $table)
    {
        return $table->renderTable();
    }

    public function create()
    {
        return CouponApplicableForm::make()->renderForm();
    }

    public function store(CouponApplicableRequest $request)
    {
        CouponApplicable::query()->create($request->validated());

        return redirect()->route('admin.coupon-applicables.index')
            ->with('success', 'Coupon applicable created successfully.');
    }

    public function show(CouponApplicable $couponApplicable)
    {
        return CouponApplicableForm::make()
            ->createWithModel($couponApplicable)
            ->renderForm();
    }

    public function edit(CouponApplicable $couponApplicable)
    {
        return CouponApplicableForm::make()->createWithModel($couponApplicable)->renderForm();
    }

    public function update(CouponApplicableRequest $request, CouponApplicable $couponApplicable)
    {
        $couponApplicable->update($request->validated());

        return redirect(request()->input('_previous_url') ?? route('admin.coupon-applicables.index'))->with('success', 'Coupon applicable updated successfully.');
    }

    public function destroy(CouponApplicable $couponApplicable)
    {
        $couponApplicable->delete();

        return response()->json([
            'error' => false,
            'data' => null,
            'message' => 'Coupon applicable deleted successfully',
        ]);
    }
}
