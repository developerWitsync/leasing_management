@extends('layouts.admin.login')

@section('title')
    Admin | Login
@endsection

@section('content')
    <div class="container h-100 mt-5">
        <div class="row h-100 justify-content-center align-items-center">
            <div class="card" style="width: 40%">
                <h4 class="card-header">Login</h4>

                <div class="card-body">

                    @if(session('info'))
                        <div class="alert alert-danger" role="alert">
                            <h5 class="alert-heading">Error</h5>
                            <p>{{ session('info') }}</p>
                        </div>
                    @endif

                    <form method="post" action="{{ route('admin.auth.login') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="email">Login Email</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="fa fa-envelope-open-o" aria-hidden="true"></i></span>
                                        </div>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                                    </div>
                                    @if($errors->has('email'))
                                        <div class="help-block with-errors text-danger">
                                            {{ $errors->first('email') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="inputPassword">Password</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="fa fa-unlock" aria-hidden="true"></i></span>
                                        </div>
                                        <input type="password" id="inputPassword" name="password" class="form-control">
                                    </div>
                                    @if($errors->has('password'))
                                        <div class="help-block with-errors text-danger">
                                            {{ $errors->first('password') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="checkbox checkbox-primary">
                                <input id="checkbox_remember" value="1" type="checkbox" name="remember">
                                <label for="checkbox_remember"> Remember me</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <input type="submit" class="btn btn-primary btn-lg btn-block" value="Login" name="submit">
                            </div>
                        </div>
                    </form>

                    <div class="clear"></div>
                    <i class="fa fa-undo fa-fw"></i> Forgot password? <a href="{{ route('admin.password.reset') }}">Reset password</a>
                </div>

            </div>

        </div>
    </div>
@endsection
