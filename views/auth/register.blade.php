 @extends('auth')
 @section('title')
  Register
 @stop
 @section('content')
	
	<form method="POST" action="/auth/register">
	  {!! csrf_field() !!}
	  <div id="register_logo" class="logo text-center">
	      <img src="{{ asset('images/navibar/uls_logo.png') }}"/>
	  </div>
	  <div class="input-group col-md-12 col-ms-12 col-xs-12">
	      <span class=""></span>
	      <input type="text" name="name" id="name" placeholder="Name" value="{{ old('name') }}">
	  </div>
	  <div class="input-group col-md-12 col-ms-12 col-xs-12">
	      <span class=""></span>
	      <input type="email" name="email" id="email" placeholder="Email Address" value="{{ old('email') }}">
	  </div>
	  <div class="input-group col-md-12 col-ms-12 col-xs-12">
	        <span class=""></span>
	        <input type="password" name="password" id="password" value="" placeholder="Password">
	  </div>
	       <div class="input-group col-md-12 col-ms-12 col-xs-12">
	        <span class=""></span>
	        <input type="password" name="password_confirmation" id="password" value="" placeholder="Password Again">
	  </div>
	  <div class=" actionregister text-center">
	      <button type="submit" class="btn btn-primary" name="btn_register">Register</button>
	      <a href="login"><span type="submit" class="btn btn-primary button-o p-t-3" name="btn_login">Login</span></a> 
	  </div>
	  <div class="forgot-login text-center">
	      <a id="reset_pwd" href="/password/email">Forgot password?</a>
	  </div>
	</form>
 @stop
 