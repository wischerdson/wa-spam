<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		Sanctum::ignoreMigrations();
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		// \DB::listen(function ($query) {
		// 	\Log::debug([
		// 		'time' => $query->time,
		// 		'sql' => $query->sql,
		// 		'params' => $query->bindings
		// 	]);
		// });
	}
}
