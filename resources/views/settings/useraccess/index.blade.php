@extends('layouts.app')
@section('content')
        <div class="panel panel-default">
            {{--<div class="panel-heading">User Access Settings</div>--}}
            <div class="panel-body">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                 @include('settings._menubar')
                 <div class="">
                    <div role="tabpanel" class="tab-pane active">
                        User Access Settings
                        <ul>
                            <li><a href="{{ route('settings.role') }}"> Manage Roles</a></li>
                            <li><a href="{{ route('settings.user') }}">Manage Users</a></li>
                        </ul>
                     </div>
                </div>
            </div>
        </div>
@endsection
