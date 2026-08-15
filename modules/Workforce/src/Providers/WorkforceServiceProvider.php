<?php

namespace Modules\Workforce\Providers;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Modules\Shared\Menu\MenuRegistry;
use Modules\Shared\Tables\Registry\TableRegistry;
use Modules\Workforce\Admin\Tables\StaffReviewTable;
use Modules\Workforce\Admin\Tables\StaffTable;

class WorkforceServiceProvider extends ServiceProvider
{
    public function boot(TableRegistry $tables, MenuRegistry $menus): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'workforce');
        $this->loadRoutesFrom(__DIR__.'/../../routes/admin.php');

        User::resolveRelationUsing('staff', fn (User $user) => $user->hasOne(Staff::class));

        $tables->register('staffs', StaffTable::class, 'figure-admin/workforce');
        $tables->register('staff-reviews', StaffReviewTable::class, 'figure-admin/workforce');
        $menus->register('workforce', [
            'name' => 'Staff', 'icon' => 'menu-icon tf-icons bx bx-group',
            'route' => 'admin.staffs.index', 'active' => ['admin.staffs.*', 'admin.staff-reviews.*'],
            'children' => [
                ['name' => 'Staffs', 'route' => 'admin.staffs.index', 'active' => ['admin.staffs.*']],
                ['name' => 'Staff Reviews', 'route' => 'admin.staff-reviews.index', 'active' => ['admin.staff-reviews.*']],
            ],
        ], 800, 'figure-admin/workforce');
    }
}
