<?php

namespace App\Providers;

use App\Domain\Course\Repository\CourseRepository;
use App\Domain\Course\Repository\CourseRepositoryImpl;
use App\Domain\Course\Service\CourseService;
use App\Domain\Course\Service\CourseServiceImpl;
use App\Domain\User\Repository\UserRepository;
use App\Domain\User\Repository\UserRepositoryImpl;
use App\Domain\User\Service\UserService;
use App\Domain\User\Service\UserServiceImpl;
use Illuminate\Support\ServiceProvider;

class CourseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind(CourseService::class, CourseServiceImpl::class);
        $this->app->bind(CourseRepository::class, CourseRepositoryImpl::class);
        
        $this->app->bind(UserService::class, UserServiceImpl::class);
        $this->app->bind(UserRepository::class, UserRepositoryImpl::class);       
        
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
