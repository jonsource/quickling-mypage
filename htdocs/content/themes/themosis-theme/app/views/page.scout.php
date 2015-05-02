@extends('layouts.main')

@if($post)
    @section('main')
        <h1>{{$post->post_title}}</h1>
        <p>{{$post->post_content}}</p>
@stop
@endif

@if($sidebar)
    @section('sidebar')
        <h1>{{$sidebar->post_title}}</h1>
        <p>{{$sidebar->post_content}}</p>
        <a data-ql-container=".ql-sidebar" href="/sidebar-1">side 1</a>
        <a data-ql-container=".ql-sidebar" href="/sidebar-2">side 2</a>
    @stop
@endif