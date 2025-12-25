<?php

class Blog extends Controller{

    public function index($a = '', $b = '' , $c = ''){
        $this->view('Traveller/blog');
    }
}