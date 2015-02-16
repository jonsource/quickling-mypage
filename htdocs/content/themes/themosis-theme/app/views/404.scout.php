@extends('layouts.main')

@section('main')
    <h1>404</h1>
    <p>{{$path}} not found on this server</p>
    <a data-ql-follow="false" class="btn" href="/">return to home</a>
@stop

