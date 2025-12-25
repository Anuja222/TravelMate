<?php

class Feed extends Controller{

    public function index($a = '', $b = '' , $c = ''){
        $this->view('Traveller/feed');
    }
}