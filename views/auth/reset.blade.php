@extends(getapp())
@section('content')
<div class="auth-reset-wrap container-fluid">
	<div class="col-md-6 col-md-offset-3">
			<div class="error-reset-mg">
				@if (count($errors) > 0)
					<div class="alert alert-danger">
						<strong>Whoops!</strong> There were some problems with your input.<br><br>
						<ul>
							@foreach ($errors->all() as $error)
								<li>{{ ($error == 'This password reset token is invalid.')? 'The password reset token has already been used, or is invalid.': $error }}</li>
							@endforeach
						</ul>
					</div>
				@endif
			</div>
			<div class="reset-wrap">
				<div class="row">

					<div class="panel panel-default reset-wrap-panel">
						<div id="login_logo">
		                    {{-- <img src="images/log_logo.png"/> --}}
		                </div>
						<form class="form-horizontal" role="form" method="POST" action="/password/reset">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<input type="hidden" name="token" value="{{ $token }}">
							<div class="form-group">
								<div class="input-group form-email">
			                        <span class="input-group-addon glyphicon glyphicon-user"></span>
			                        <input type="email"  placeholder="Login E-mail" class="form-control" name="email" id="email" value="{{ old('email') }}">
			                    </div>
		                    </div>
		                    <div class="form-group">
								<div class="input-group form-password">
			                        <span class="input-group-addon glyphicon glyphicon-lock" id="basic-addon1"></span>
			                        <input type="password" placeholder="Password" class="form-control" name="password" id="password" value="{{ old('password') }}">
			                    </div>
			                </div>
			                <div class="form-group">
			                    <div class="input-group form-password-confirm">
			                        <span class="input-group-addon  glyphicon glyphicon-lock" id="basic-addon1"></span>
			                        <input type="password" placeholder="Password confirm" class="form-control" name="password_confirmation" id="password_confirmation" value="{{ old('password_confirmation') }}">
			                    </div>
			                </div>
							<button type="submit" class="btn btn-primary">
								Set Password
							</button>
						</form>
					</div>
				</div>
			</div>
	</div>
</div>
@endsection
