<?php

/*----------------------------------------------------*/
// Production config
/*----------------------------------------------------*/
// Database
define('DB_NAME', getenv('DB_NAME'));
define('DB_USER', getenv('DB_USER'));
define('DB_PASSWORD', getenv('DB_PASSWORD'));
define('DB_HOST', getenv('DB_HOST') ? getenv('DB_HOST') : 'localhost');

// WordPress URLs
define('WP_HOME', getenv('WP_HOME'));
define('WP_SITEURL', getenv('WP_SITEURL'));

// Production
/*define('WP_DEBUG', true);
define('WP_DEBUG_DISPLAY', false);
define('WP_DEBUG_LOG', true);
define('SCRIPT_DEBUG', false);*/

// Development
define('WP_DEBUG', true);
define('WP_DEBUG_DISPLAY', true);
define('SCRIPT_DEBUG', true);
define('WP_DEBUG_LOG', true);

// Themosis framework - Production
/*define('THEMOSIS_ERROR_DISPLAY', false);
define('THEMOSIS_ERROR_SHUTDOWN', false);
define('THEMOSIS_ERROR_REPORT', 0);*/

// Themosis framework - Development
define('THEMOSIS_ERROR_DISPLAY', true);
define('THEMOSIS_ERROR_SHUTDOWN', true);
define('THEMOSIS_ERROR_REPORT', -1);