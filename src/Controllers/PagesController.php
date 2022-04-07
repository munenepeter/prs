<?php

namespace PRS\Controllers;

class PagesController {

    public function index(){

        return view('home');
    }
    public function login(){

        return view('login');
    }


}