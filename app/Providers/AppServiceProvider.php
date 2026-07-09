<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Relations\Relation;
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
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Admin') ? true : null;
        });

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        Relation::enforceMorphMap([
            'livestock' => 'App\Models\Livestock',
            'milking' => 'App\Models\Milking',
            'abort' => 'App\Models\Abort',
            'semen_batch' => 'App\Models\SemenBatch',
            'embrion_batch' => 'App\Models\EmbrionBatch',
            'service' => 'App\Models\Service',
            'extraction' => 'App\Models\Extraction',
            'revision' => 'App\Models\Revision',
            'birth' => 'App\Models\Birth',
            'treatment_application' => 'App\Models\TreatmentApplication',
            'sanitary_plan' => 'App\Models\SanitaryPlan',
            'supply' => 'App\Models\Supply',
            'product' => 'App\Models\Product',
            'clinic_history' => 'App\Models\ClinicHistory',
            'product_movement' => 'App\Models\ProductMovement',
            'supply_movement' => 'App\Models\SupplyMovement',
            'user' => 'App\Models\User',
        ]);

        Relation::requireMorphMap();
    }
}
