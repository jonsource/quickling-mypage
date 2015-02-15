<?php

/*
 * Define your routes and which views to display
 * depending of the query.
 *
 * Based on WordPress conditional tags from the WordPress Codex
 * http://codex.wordpress.org/Conditional_Tags
 *
 */

//die('zlo');

Route::get('front', function($post){
    $is_update = Input::get('update',false);
    $first_load = 0;
    if(!$is_update)
    {	$first_load = 1;
    }

    return View::make('front',array('post'=>$post, 'first_load'=>$first_load));

});

Route::get('page', function($post){
	$is_update = Input::get('update',false);
	$first_load = 0;
	if(!$is_update)
	{	$first_load = 1;
	}
    $sidebar = null;
    $title = $post->post_title;
    $parts = explode(' ',$title);
    if($parts[0]=="Sidebar") {
        return View::make('page',array('post'=>null, 'sidebar'=>$post, 'first_load'=>$first_load));
    }
    if($parts[0]=="Themosis") {
        $sidebar = get_page_by_title('Sidebar 1');
    } else {
        $sidebar = get_page_by_title('Sidebar 2');
    }

    return View::make('page',array('post'=>$post, 'sidebar'=>$sidebar, 'first_load'=>$first_load));

});
