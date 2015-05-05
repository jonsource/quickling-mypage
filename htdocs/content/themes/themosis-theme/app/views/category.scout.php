@extends('layouts.main')

@if($page)
    @section('main')
        <h1>{{$page->post_title}}</h1>
        <p>{{ apply_filters('the_content', $page->post_content) }}</p>
    @stop
@endif

@if($posts)
    @section('sidebar')
        <ul>
        @foreach($posts as $post)
            <li>
            <a data-ql-target=".ql-cont" href="{{path()}}/{{$post->post_name}}">{{$post->post_title}}</a>
            </li>
        @endforeach
        </ul>
    @stop
@endif