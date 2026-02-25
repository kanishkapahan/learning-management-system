<?php

namespace App\Providers;

use App\Repositories\Contracts\ExamRepositoryInterface;
use App\Repositories\Contracts\ResultRepositoryInterface;
use App\Repositories\Contracts\StudentRepositoryInterface;
use App\Repositories\Eloquent\ExamRepository;
use App\Repositories\Eloquent\ResultRepository;
use App\Repositories\Eloquent\StudentRepository;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);
        $this->app->bind(ExamRepositoryInterface::class, ExamRepository::class);
        $this->app->bind(ResultRepositoryInterface::class, ResultRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        date_default_timezone_set(config('app.timezone', 'UTC'));

        Password::defaults(function () {
            return Password::min(10)
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised();
        });
    }
}
