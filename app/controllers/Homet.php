<?php

class Homet extends Controller{

    public function index($a = '', $b = '' , $c = ''){
        $this->view('Traveller/homet');
    }
}