@extends('layouts.app')

@section('content')
<div class="loginOuter">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default loginInnerBx resetPadd">
                    <div class="loginLogo">
                        <a href="/"><img src="{{ asset('assets/images/logo.png')}}" alt="Logo"></a>
                    </div>
                    <div class="panel-heading">Reset Password</div>

                    <div class="panel-body loginPanel">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-12 control-label">E-Mail Address</label>

                                <div class="col-md-12">
                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12 btnAlign">
                                    <button type="submit" class="btn btn-primary loginBtn">
                                        Send Password Reset Link
                                    </button>
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
