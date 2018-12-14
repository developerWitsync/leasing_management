@extends('layouts.admin._blank')

@section('title')
    Admin | Preview Email Template | {{ ucwords($template->title) }}
@endsection

@section('content')
    {!! $template->template_body !!}
@endsection