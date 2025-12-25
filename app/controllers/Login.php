<?php

class Login extends Controller{

    public function index($a = '', $b = '' , $c = ''){
        $this->view('Traveller/login');
    }
}