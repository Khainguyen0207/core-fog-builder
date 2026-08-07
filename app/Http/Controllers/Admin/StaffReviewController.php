<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Forms\StaffReviewForm;
use App\Admin\Tables\StaffReviewTable;
use App\Http\Controllers\Controller;
use App\Models\StaffReview;

class StaffReviewController extends Controller
{
    public function index(StaffReviewTable $table)
    {
        return $table->renderTable();
    }

    public function show(StaffReview $staffReview)
    {
        return StaffReviewForm::make()
            ->createWithModel($staffReview->loadMissing(['customer', 'staff', 'bookingService']))
            ->renderForm();
    }
}
