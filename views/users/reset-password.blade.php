@extends('core::auth')
@section('title')
    Send Password Reset Link
@stop
@section('content')
    
    <fieldset class="boxBody">
        <form method="POST" action="/password/email" novalidate>
            {!! csrf_field() !!}
            <div class="input-group">
                <span class="input-group-addon glyphicon glyphicon-user"></span>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Your Email">
            </div>
            <div class=" actionlogin text-center">
                <button type="submit" class="btn btn-primary" name="btn_login">Send Password Reset Link</button>
            </div>
        </form>
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </fieldset>
    
@stop