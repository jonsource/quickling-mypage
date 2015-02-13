<?php
    require_once("includes/common.php");
    if(is_admin())
    {   require_once("includes/backend.php");
    } else {
        require_once("includes/frontend.php");
    }
