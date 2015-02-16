<?php

/*
 * Define your routes and which views to display
 * depending of the query.
 *
 * Based on WordPress conditional tags from the WordPress Codex
 * http://codex.wordpress.org/Conditional_Tags
 *
 */

/* check first load, and put info into cookie */
if(isset($_SERVER['HTTP_X_QL_UPDATE']) && $_SERVER['HTTP_X_QL_UPDATE']) {
    View::share('is_update', true);
}

Route::get('front', function($post){
    return View::make('front',array('post'=>$post));
});

Route::get('page', function($post){
    $sidebar = null;
    $title = $post->post_title;
    $parts = explode(' ',$title);
    if($parts[0]=="Sidebar") {
        return View::make('page',array('post'=>null, 'sidebar'=>$post));
    }
    if($parts[0]=="Themosis") {
        $sidebar = get_page_by_title('Sidebar 1');
    } else {
        $sidebar = get_page_by_title('Sidebar 2');
    }

    return View::make('page',array('post'=>$post, 'sidebar'=>$sidebar));

});

Route::any('404', function($post){
    $custom_routes = array(
        '/about'=> 'CustomController@about',
    );

    $uri = $_SERVER["REQUEST_URI"];
    $parts = explode('?',$uri);
    $path = $parts[0];
    if(array_key_exists($path, $custom_routes))
    {   status_header(200);
        if(is_string($custom_routes[$path])) {
            $call_parts = explode('@',$custom_routes[$path]);
            $controller = new $call_parts[0];
            return $controller->$call_parts[1](null,null);
        } else if(is_callable($custom_routes[$path])) {
            return $custom_routes[$path](null, null);
        }
    }
    return View::make('404',array('path'=>$path));
});