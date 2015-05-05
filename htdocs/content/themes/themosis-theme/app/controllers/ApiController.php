<?php

class ApiController extends BaseController
{
    private static $mapping_array = array(
        'post' => 'PostModel',
        'product' => 'ProductModel'
    );

    public static function route($model,$id) {

        $current_user = wp_get_current_user();

        if(!$model || !isset(static::$mapping_array[$model])) {
            return json_encode(array('success'=>'false','error'=>'Unknown model: '.$model));
        }

        if($_SERVER["REQUEST_METHOD"]=='GET') {
            if($id)
            {
                $ret = call_user_func(static::$mapping_array[$model].'::all');
            } else {
                $ret = call_user_func(static::$mapping_array[$model].'::get',array($id));
            }
        }

        return json_encode(array('success'=>'true',$model=>$ret));
    }
} 