@extends('layouts.app')

@section('content')
        <div class="panel panel-default">
            <div class="panel-heading">Settings | Codification Settings</div>

            <div class="panel-body">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                @include('settings._menubar')

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active">
                        Codification Settings
                    </div>
                </div>
            </div>
        </div>
@endsection
