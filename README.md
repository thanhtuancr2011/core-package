# Pull Package To Your Project From Github:

 	Step 1: Open composer.json file and add:
 		1. Add content to repositories and save:
	 		"core-package": {
	            "type": "package",
	            "package": {
	                "name": "comus/core",
	                "version": "1.0.0",
	                "source": {
	                    "url": "https://github.com/thanhtuancr2011/core-package",
	                    "type": "git",
	                    "reference": "master"
	                }
	            }
	        }
	    2. Add content to require and save:
	    	"comus/core": "1.0.0",
	    	"laravelcollective/html": "5.3.*"
	    	"intervention/image": "~2.1",
	        "bican/roles": "2.1.*",
	        "gloudemans/shoppingcart": "~1.3",
	        "laravel/socialite": "~2.0"
	    
	Step 2: 
		1. Run command 
			composer update => The package core will download into vendor/comus/core.
			php artisan vendor:publish

# Configure package:

	    Step 1: Open file database/seeds/DatabaseSeeder.php
	    	1. Add contents before class DatabaseSeeder:
	    		use Comus\Core\Database\UserTableSeeder;
	    	2. Add content in class DatabaseSeeder:
	    		$this->call(UsersTableSeeder::class); 

		Step 2: Run command:
			1. Create tables in database
	   			php artisan migrate --path="vendor/comus/core/database/migrations"
	   		2. Create default user, role and permission
	   			php artisan db:seed

	   	Step 3: Open file config/app.php 
			1. Insert contents to providers:
				Collective\Html\HtmlServiceProvider::class,
		        Intervention\Image\ImageServiceProvider::class,
		        Gloudemans\Shoppingcart\ShoppingcartServiceProvider::class,
				/* Package  core */
		        Comus\Core\CoreServiceProvider::class,
		        Bican\Roles\RolesServiceProvider::class,
		        Laravel\Socialite\SocialiteServiceProvider::class
		    2. Insert contents to aliases:
		    	'Form'      => Collective\Html\FormFacade::class,
		        'Html'      => Collective\Html\HtmlFacade::class,
		        'Image'     => Intervention\Image\Facades\Image::class,
		        'Socialite' => Laravel\Socialite\Facades\Socialite::class,
		        'Cart'      => Gloudemans\Shoppingcart\Facades\Cart::class

	2. Install Roles And Permissions For Laravel 5

	composer require bican/roles:2.1.*

	Add package users to file composer.json
	
	"psr-4": {
		"Comus\\Core\\": "packages/core"
	}
	
	Then in your config/app.php add
	
	Bican\Roles\RolesServiceProvider::class,
    Comus\Core\CoreServiceProvider::class

    to provider array

    And add

    'Image'     => Intervention\Image\Facades\Image::class

    to aliases array

    You must change the content of the file app/Http/Middleware/Authenticate.php

	return redirect()->guest('login');

	to

	return redirect()->guest('auth/login');

# Bower  

	Edit file .bowerrc to 

    {
	  "directory": "public/bower_components"
	} 

	Add framework and plugin to file bower.json

	"angular": "1.4.2",
    "angular-bootstrap": "~0.14.3",
    "angular-resource": "~1.3.14",
    "angular-sanitize": "~1.3.15",
    "angular-xeditable": "~0.1.8",
    "awesome-bootstrap-checkbox": "~0.3.4",
    "bower": "*",
    "humanize-duration": "~3.2.0",
    "install": "~1.0.4",
    "jquery-ui": "~1.11.4",
    "moment": "~2.10.3",
    "ng-file-upload": "~5.0.9",
    "ng-table": "~0.5.4",
    "ngImgCrop": "~0.3.2",
    "select2": "~4.0.0",
    "bootstrap": "~3.3.5",
    "fontawesome": "~4.4.0",
    "ui-iconpicker": "~0.1.4",
    "ckeditor": "#full/4.3.3",
    "components-font-awesome": "~4.4.0",
    "metisMenu": "2.0.2"
	
	And run bower update in command
	
# Configuation

	Publish the package config file and migrations and resources to your application. Run these commands inside your terminal
	
	php artisan vendor:publish --provider="Comus\Core\CoreServiceProvider"
	
	public files migrations
	php artisan vendor:publish --provider="Comus\Core\CoreServiceProvider" --tag='migrations'
	
	public files config
	php artisan vendor:publish --provider="Comus\Core\CoreServiceProvider" --tag='config'

	public resource
	php artisan vendor:publish --provider="Comus\Core\CoreServiceProvider" --tag='resource'

# Usage
	## Include js
	    {!! Html::script('js/vendor-core.min.js')!!}
	    {!! Html::script('bower_components/angular/angular.js')!!}
        {!! Html::script('bower_components/angular-resource/angular-resource.js')!!}
        {!! Html::script('bower_components/angular-bootstrap/ui-bootstrap.js')!!}
        {!! Html::script('bower_components/angular-bootstrap/ui-bootstrap-tpls.js')!!}
        {!! Html::script('bower_components/angular-xeditable/dist/js/xeditable.js') !!}
        {!! Html::script('bower_components/ngImgCrop/source/js/init.js')!!}
        {!! Html::script('bower_components/ngImgCrop/source/js/ng-img-crop.js')!!}
        {!! Html::script('bower_components/ngImgCrop/compile/minified/ng-img-crop.js')!!}
        {!! Html::script('bower_components/ng-table/dist/ng-table.js') !!}
        {!! Html::script('app/lib/angular-file-upload-shim.min.js')!!}
        {!! Html::script('app/lib/angular-file-upload.min.js')!!}
        {!! Html::script('app-users-rowboat/app.js')!!}
        {!! Html::script('app-users-rowboat/config.js')!!} 
        {!! Html::script('bower_components/jquery.maskedinput/dist/jquery.maskedinput.min.js')!!}
	## Include css
	    {!! Html::style('bower_components/bootstrap/dist/css/bootstrap.min.css')!!}
	    {!! Html::style('bower_components/components-font-awesome/css/font-awesome.min.css')!!}
	    {!! Html::style('bower_components/ng-table/dist/ng-table.min.css') !!}
	    {!! Html::style('bower_components/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') !!}
	    {!! Html::style('css/rowboat/user.css')!!}
# License
	This package is private software distributed under the terms of the MIT license.