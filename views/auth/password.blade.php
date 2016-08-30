@extends(getapp())

@section('content')
<div class="auth-sent-wrap">
	<div class="col-md-6 col-md-offset-3">
		<div class="error-sent-mg">
		@if (session('status'))
			<div class="alert alert-success">
			{{ session('status') }}
			</div>
		@endif
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
		<div class="sent-wrap">
			<div class="row">
				<div class="panel panel-default sent-wrap-panel">
					<div id="login_logo">
	                    
	                </div>
					<form class="form-horizontal" role="form" method="POST" action="/password/email">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<div class=" form-group input-group form-email">
	                         <span class="input-group-addon glyphicon glyphicon-user"></span>
	                        <input type="email" class="form-control" name="email" id="uname" value="{{ old('email') }}" placeholder="Login E-mail">
	                    </div>
						<button type="submit" class="btn btn-primary">
							Send Password Reset Link
						</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
