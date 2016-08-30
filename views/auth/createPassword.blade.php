@extends(getapp())
@section('content')
<div class="auth-reset-wrap">
<div class="col-md-6 col-md-offset-3">
	<div class="error-reset-mg">
	@if (count($errors) > 0)
		<div class="alert alert-danger">
			<strong>Whoops!</strong> There were some problems with your input.<br><br>
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif
	</div>
	<div class="reset-wrap">
		
			<div class="panel panel-default" style="border-radius:0px;">
				<div class="panel-heading"><h4 class="margin-none">Create Password</h4></div>
				<div class="panel-body" style="padding:20px 50px;">
					<form class="form-horizontal" role="form" method="POST" action="/password/reset">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="token" value="{{ $token }}">

						<div class="form-group">
							<div class="input-group form-email_1">
	                            <span class="input-group-addon" id="basic-addon1">@</span>
	                            <input type="email" placeholder="Email" class="form-control" name="email" id="email" value="{{ old('email') }}">
	                        </div>
						</div>

						<div class="form-group">
							<div class="input-group form-password_1">
	                            <span class="input-group-addon glyphicon glyphicon-lock" id="basic-addon1" style="top:0px;"></span>
	                            <input type="password" placeholder="Password" class="form-control" name="password" id="password" value="{{ old('password') }}">
	                        </div>
						</div>

						<div class="form-group">
							<div class="input-group form-password-confirm_1">
	                            <span class="input-group-addon glyphicon glyphicon-lock" id="basic-addon1" style="top:0px;"></span>
	                            <input type="password" placeholder="Password confirm" class="form-control" name="password_confirmation" id="password_confirmation" value="{{ old('password_confirmation') }}">
	                        </div>
						</div>

						<div class="form-group text-center">
							<button type="submit" class="btn btn-primary">
								Submit
							</button>
						</div>
					</form>
				</div>
			</div>
		
	</div>
	</div>
</div>
@endsection


