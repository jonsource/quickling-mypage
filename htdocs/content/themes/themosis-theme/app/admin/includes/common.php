<?php
/**
 * application.php - Write your custom code below.
 */

function path() {
    return getenv('WP_HOME');
}

function _pa() {
    return path().'/content/themes/themosis-theme/app/assets';
}

function _tp($name) {
    return themosis_path($name);
}