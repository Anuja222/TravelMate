<?php

class Beach extends Controller{

    public function index($a = '', $b = '' , $c = ''){
        $this->view('Traveller/beach');
    }
}