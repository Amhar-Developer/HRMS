<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\LeaveRequest;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        return [
            Stat::make('Total Employees', User::count())
                ->description('Active Employees in System.')
                ->descriptionIcon('heroicon-o-users')
                ->color('info'),

            Stat::make('Total Department', Department::count())
                ->description('Number of Departments')
                ->descriptionIcon('heroicon-o-Building-Office')
                ->color('info'),

            Stat::make('Pending Leave Request', LeaveRequest::where('status', 'pending')->count())
                ->description('Awaiting Approval')
                ->descriptionIcon('heroicon-o-calendar-days')
                ->color('primary'),

            Stat::make(
                'Today Attendance',
                Attendance::whereDate('date', today())->count()   )
                ->description('Checked in Today')
                ->descriptionIcon('heroicon-o-clock')
                ->color('primary'), 
        ];
    }
}
