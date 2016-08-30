<?php 

namespace Comus\Core;

use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		if (!$this->app->routesAreCached()) {
	        require __DIR__.'/routes.php';
	    }
	    
		$this->loadViewsFrom(base_path() . '/resources/views/vendor/core', 'core');

		$this->publishes([
		    __DIR__.'/Database/migrations' => base_path('database/migrations')
		]);

		$this->publishes([
		    __DIR__.'/views' => base_path('resources/views/vendor/core')
		]);	

	    $this->publishes([
	        __DIR__.'/public' => base_path('public')   
	    ]);

	    $this->publishes([
	        __DIR__.'/config' => base_path('config')   
	    ]);
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		foreach (glob(__DIR__.'/Helpers/*.php')  as $filename){
         	require_once($filename);
     	}
	}

}
