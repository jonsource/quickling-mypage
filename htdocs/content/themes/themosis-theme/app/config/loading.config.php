<?php

return array(

    /**
     * Mapping for all classes without a namespace.
     * The key is the class name and the value is the
     * absolute path to your class file.
     *
     * Watch your commas...
     */
    // Controllers
    'BaseController'        => themosis_path('app').'controllers'.DS.'BaseController.php',
    'CustomController'      => themosis_path('app').'controllers'.DS.'CustomController.php',
    'ApiController'      => themosis_path('app').'controllers'.DS.'ApiController.php',

    // Models
    'PostModel'             => themosis_path('app').'models'.DS.'PostModel.php',

    // Miscellaneous

);