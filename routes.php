<?php

Route::group(['middleware' => ['web']], function () {
	/* Route social login */
	Route::get('/redirect/{provider}', 'SocialAuthController@redirect');
	Route::get('/callback/{provider}', 'SocialAuthController@callback');

	/* Route login */
    Route::get('auth/login', 'Comus\Core\Http\Auth\AuthController@getLogin');
    Route::post('auth/login', 'Comus\Core\Http\Auth\AuthController@postLogin');

    /* Route logout */
	Route::get('auth/logout', 'Comus\Core\Http\Auth\AuthController@getLogout');

	/* Route logout */
	Route::get('auth/logout', 'Comus\Core\Http\Auth\AuthController@getLogout');

	/* Route admin */
	Route::group(['prefix' => 'admin'], function() {
		
		/* Route route */
		Route::resource('role','Comus\Core\Http\Controllers\RoleController');

		/* Permission route */
		Route::resource('permission','Comus\Core\Http\Controllers\PermissionController');

		/* Route Article */
		Route::resource('article','Comus\Core\Http\Controllers\ArticleController');

		/* Route Collection */
		Route::resource('collection','Comus\Core\Http\Controllers\CollectionController');

		/* Route Page */
		Route::resource('page','Comus\Core\Http\Controllers\PageController');

		/* Route show permission user */
		Route::get('user/show-permissions/{id}','Comus\Core\Http\Controllers\UserController@permissions');
		
		/* User route */
		Route::resource('user','Comus\Core\Http\Controllers\UserController');

		/* Category route */
		Route::resource('category', 'Comus\Core\Http\Controllers\CategoryController');

		/* Product route */
		Route::resource('product', 'Comus\Core\Http\Controllers\ProductController');

		/* Product order */
		Route::resource('order', 'Comus\Core\Http\Controllers\OrderController');
	});

	/*  Route API */
	Route::group(['prefix' => 'api'], function(){
		
		/* Route change avatar and password for user */
		Route::post('user/change-password','Comus\Core\Http\Controllers\Api\UserController@changePassword');
		Route::post('user/change-avatar/{id?}','Comus\Core\Http\Controllers\Api\UserController@changeAvatar');

		/* Route update roles and permissions */
		Route::post('user/update-permission/{id}','Comus\Core\Http\Controllers\Api\UserController@updatePermission');
		Route::post('user/update-role/{id}','Comus\Core\Http\Controllers\Api\UserController@updateRole');

		/* Route user */
		Route::resource('user','Comus\Core\Http\Controllers\Api\UserController');
		
		/* Route check email exists */
		Route::post('user/profile/check-email','Comus\Core\Http\Controllers\Api\UserController@checkEmailProfile');
		
		/* Route route */
		Route::resource('role', 'Comus\Core\Http\Controllers\Api\RoleController');

		/* Permission route */
		Route::resource('permission', 'Comus\Core\Http\Controllers\Api\PermissionController');

		/* Route Article */
		Route::post('article/file','Comus\Core\Http\Controllers\Api\ArticleController@storeImage');
		Route::post('article/create-image-article/{id}', 'Comus\Core\Http\Controllers\Api\ArticleController@createImageArticle');
		Route::post('article/update-image-article/{id}', 'Comus\Core\Http\Controllers\Api\ArticleController@updateImageArticle');
		Route::resource('article','Comus\Core\Http\Controllers\Api\ArticleController');

		/* Route Collection */
		Route::post('collection/file','Comus\Core\Http\Controllers\Api\CollectionController@storeImage');
		Route::post('collection/create-image-collection/{id}', 'Comus\Core\Http\Controllers\Api\CollectionController@createImageCollection');
		Route::post('collection/update-image-collection/{id}', 'Comus\Core\Http\Controllers\Api\CollectionController@updateImageCollection');
		Route::resource('collection','Comus\Core\Http\Controllers\Api\CollectionController');

		/* Route Page */
		Route::resource('page','Comus\Core\Http\Controllers\Api\PageController');

		/* Route Category */
		Route::post('category/file','Comus\Core\Http\Controllers\Api\CategoryController@storeImage');
		Route::post('category/create-image-category/{id}', 'Comus\Core\Http\Controllers\Api\CategoryController@createImageCategory');
		Route::post('category/update-image-category/{id}', 'Comus\Core\Http\Controllers\Api\CategoryController@updateImageCategory');
		Route::resource('category', 'Comus\Core\Http\Controllers\Api\CategoryController');

		/* Route Product */
		Route::post('product/file','Comus\Core\Http\Controllers\Api\ProductController@storeImage');
		Route::post('product/create-image-product/{id}', 'Comus\Core\Http\Controllers\Api\ProductController@createImageProduct');
		Route::post('product/update-image-product/{id}', 'Comus\Core\Http\Controllers\Api\ProductController@updateImageProduct');
		Route::resource('product', 'Comus\Core\Http\Controllers\Api\ProductController');

		/* Route cart */
		Route::post('shopping-cart/{id}', 'Comus\Core\Http\Controllers\Api\CartController@addProductToCart');
		Route::resource('shopping-cart', 'Comus\Core\Http\Controllers\Api\CartController');

		/* Route customer */
		Route::post('customer/send-email/{id}', 'Comus\Core\CustomerController@sendEmailToCustomerPurchase');
		Route::post('customer/login', 'Comus\Core\CustomerController@postLogin');
		Route::resource('customer', 'Comus\Core\CustomerController');
	});

	/* Route profile user */
	Route::get('user/profile/{id}','Comus\Core\Http\Controllers\UserController@profileUser');

	/* Route change pasword */
	Route::get('user/change-password', 'Comus\Core\Http\Controllers\UserController@changePassword');

	/* Password reset link request routes */ 
	Route::get('password/email', 'Comus\Core\Http\Controllers\Auth\PasswordController@getEmail');
	Route::post('password/email', 'Comus\Core\Http\Controllers\Auth\PasswordController@postEmail');

});


// /* Registration routes */
// Route::get('auth/register', 'Comus\Core\Http\Auth\AuthController@getRegister');
// Route::post('auth/register', 'Comus\Core\Http\Auth\AuthController@postRegister');

// /* Password reset routes */
// Route::get('password/reset/{token}', 'Comus\Core\Http\Auth\PasswordController@getReset');
// Route::post('password/reset', 'Comus\Core\Http\Auth\PasswordController@postReset');




