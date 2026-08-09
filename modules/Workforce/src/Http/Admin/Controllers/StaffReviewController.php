<?php

namespace Modules\Workforce\Http\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\StaffReview;
use Modules\Workforce\Admin\Forms\StaffReviewForm;
use Modules\Workforce\Admin\Tables\StaffReviewTable;

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
