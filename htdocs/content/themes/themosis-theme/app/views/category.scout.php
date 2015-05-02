@extends('layouts.main')

@if($page)
    @section('main')
        <h1>{{$page->post_title}}</h1>
        <p>{{ apply_filters('the_content', $page->post_content) }}</p>
    @stop
@endif

@if($posts)
    @section('sidebar')
        @foreach($posts as $post)
            <a data-ql-target=".ql-cont" href="/{{$post->post_name}}">{{$post->post_title}}</a><br>
        @endforeach
    @stop
@endif