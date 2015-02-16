<?php

class CustomController extends BaseController
{
    public function about($page,$query) {
        return View::make('templates.about');
    }
} 