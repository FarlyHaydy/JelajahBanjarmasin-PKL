<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    public function index(): string
    {
        return view('user/dashboard');
    }
}
