@extends('dashboard.master')

@section('content')
	<div class="row">
        <div class="col-lg-12">
        	<h1 class="page-header">Hello {{ $user->name }}</h1>
        </div>
    </div>
@stop