<?php

class WelcomeController extends BaseController {
    public function index() {
        $this->view('login/login');
    }
}