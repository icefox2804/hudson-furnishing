<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use App\Models\SiteSetting;
use App\Helpers\PriceHelper;
use carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force Bootstrap 5 pagination view
        Paginator::defaultView('vendor.pagination.bootstrap-5');
        Paginator::defaultSimpleView('vendor.pagination.simple-bootstrap-5');
        Carbon::setLocale('vi');

        // Register custom Blade directive for price formatting
        Blade::directive('price', function ($expression) {
            return "<?php echo App\Helpers\PriceHelper::formatVND($expression); ?>";
        });

        // Share site settings with all views
        View::composer('*', function ($view) {
            $siteSettings = cache()->rememberForever('siteSettings', function () {
                return SiteSetting::pluck('value', 'key')->toArray();
            });
            $view->with('siteSettings', $siteSettings);
        });

        View::share('siteSettings', cache()->remember('site_settings', 3600, function () {
            return SiteSetting::pluck('value', 'key')->toArray();
        }));

        // Share sections with header component
        View::composer('components.header', function ($view) {
            $view->with('sections', \App\Models\Section::all());
        });
        
        // set locale cho carbon
        Carbon::setLocale('vi');

    }
}
