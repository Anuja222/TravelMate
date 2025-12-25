<?php

class Accommodation extends Controller{

    public function index($a = '', $b = '' , $c = ''){
        $this->view('Traveller/accommodation');
    }
}