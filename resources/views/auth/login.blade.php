@extends('layouts.app')

@section('content')
    <div class="loginOuter">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default loginInnerBx loginpadd">
                        <div class="loginLogo">
                            <a href="/">
                                <img src="{{ asset('assets/images/logo.png')}}" alt="Logo">
                            </a>
                        </div>
                        <div class="panel-heading">Login</div>

                        <div class="panel-body loginPanel">
                            <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                                {{ csrf_field() }}

                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                @if (session('error'))
                                    <div class="alert alert-warning">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label for="email" class="col-md-12 control-label">E-Mail Address</label>

                                    <div class="col-md-12">
                                        <input id="email" type="text" placeholder="E-Mail / Username"
                                               class="form-control" name="email" value="{{ old('email') }}" autofocus>

                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label for="password" class="col-md-12 control-label">Password</label>

                                    <div class="col-md-12">
                                        <input id="password" type="password" placeholder="Password" class="form-control"
                                               name="password">

                                        @if ($errors->has('password'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                                    <div class="col-md-12">

                                        {!! app('captcha')->display([
                                                'data-theme' => 'light',
                                                'id' => 'rc-imageselect'
                                        ]) !!}

                                        @if ($errors->has('g-recaptcha-response'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div class="checkbox clearfix">
                                            <label>
                                                <input type="checkbox"
                                                       name="remember" {{ old('remember') ? 'checked' : '' }}> Remember
                                                Me
                                            </label>
                                            <a href="{{ route('password.request') }}">Forgot Password?</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-12 btnAlign">
                                        <button type="submit" class="btn btn-primary loginBtn">
                                            Login
                                        </button>

                                        <a class="btn btn-link" href="{{ route('master.pricing.index') }}">
                                            Don't have an account? Sign Up
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer-script')

@endsection
