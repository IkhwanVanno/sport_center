<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Booking;
use App\Models\Branch;
use App\Models\Court;
use App\Models\CourtPriceRule;
use App\Models\Facility;
use App\Models\Sport;
use App\Models\User;
use App\Policies\BookingPolicy;
use App\Policies\BranchPolicy;
use App\Policies\CourtPolicy;
use App\Policies\CourtPriceRulePolicy;
use App\Policies\FacilityPolicy;
use App\Policies\SportPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        Gate::policy(Court::class, CourtPolicy::class);
        Gate::policy(CourtPriceRule::class, CourtPriceRulePolicy::class);
        Gate::policy(Booking::class, BookingPolicy::class);
        Gate::policy(Facility::class, FacilityPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Branch::class, BranchPolicy::class);
        Gate::policy(Sport::class, SportPolicy::class);
    }
}
