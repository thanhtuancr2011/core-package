@extends('core::auth')
@section('title')
    Login
@stop
@section('content')
	<form action="/auth/login" method="POST" class="box login" novalidate>
		{!! csrf_field() !!}
		<fieldset class="boxBody">
		  	<label>Email</label>
		  	<input type="text" name="email" tabindex="1" placeholder="Email" required>
		  	<label><a href="/password/email" class="rLink" tabindex="5">Forgot password?</a>Password</label>
		  	<input type="password" name="password" tabindex="2" placeholder="Mật khẩu" required>
		</fieldset>
		<footer>
		  	<label><input type="checkbox" name="remember" tabindex="3">Remember Me</label>
		  	<input type="submit" class="btnLogin" value="Login" tabindex="4">
		  	<div class="clearfix"></div>
		  	<div style="padding-top:20px;">
		  		@if (count($errors) > 0)
				    <div class="alert alert-danger">
				      	<ul style="list-style: none">
				        	@foreach ($errors->all() as $error)
				           	   	<li>
									{{ $error }}
				           	   	</li>
				          	@endforeach
				      	</ul>
				    </div>
				@endif
		  	</div>
		</footer>
	</form>
@endsection

