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

Route::get('page', array('themosis',function($page){
    $posts = get_posts( array('posts_per_page'=>-1,'category_name'=>'on-themosis') );
    return View::make('category',array('page'=>$page, 'posts'=>$posts));
}));

Route::get('single', function($post){
    $cats = wp_get_post_categories( $post->ID );
    if(sizeof($cats)) $cats = $cats[0];
    $posts = get_posts( array('posts_per_page'=>-1,'category'=>$cats) );
    return View::make('category',array('page'=>$post, 'posts'=>$posts));
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

Route::any('404', function(){
    $custom_routes = array(
        '/about'=> 'CustomController@about',
        '/about/more'=> function() { return View::make('templates.about-more'); },
    );

    $parts = explode('?',$_SERVER["REQUEST_URI"]);
    $path = str_replace(array('htdocs/','mypage/'),'',$parts[0]);

    if(array_key_exists($path, $custom_routes))
    {
        status_header(200);
        if(is_string($custom_routes[$path]))
        {
            $call_parts = explode('@',$custom_routes[$path]);
            $controller = new $call_parts[0];
            return $controller->$call_parts[1](null,null);
        }
        else if(is_callable($custom_routes[$path]))
        {
            return $custom_routes[$path](null, null);
        }
    }

    $path_parts = explode('/',$path);
    if($path_parts[1]=='api') {
        $controller = 'ApiController';
        $model = null;
        $id = null;
        if(isset($path_parts[2])) $model = $path_parts[2];
        if(isset($path_parts[3])) $id = $path_parts[3];
        return call_user_func($controller.'::route',$model,$id);
    }

    return View::make('404',array('path'=>$path));
});