<?php

namespace App\Filament\Resources\Categories\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use App\Models\Category;

class CategoriesStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Categories', Category::count())
                ->description('Active categories in the system')
                ->color('primary'),

            Stat::make('Parent Categories', Category::whereNull('parent_id')->count())
                ->description('Root level categories')
                ->color('success'),

            Stat::make('Subcategories', Category::whereNotNull('parent_id')->count())
                ->description('Child categories')
                ->color('info'),

            Stat::make('Archived / Deleted', Category::onlyTrashed()->count())
                ->description('Categories in trash')
                ->color('danger'),
        ];
    }
}
