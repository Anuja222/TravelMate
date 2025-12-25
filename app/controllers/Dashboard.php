<?php

class Dashboard extends Controller{

    public function index($a = '', $b = '' , $c = ''){
        $this->view('Traveller/dashboard');
    }
}