<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class ProfilController extends BaseController
{
    public function index()
    {
        // Mengambil data dari session
        $data = [
            'username'     => session()->get('username'),
            'role'         => session()->get('role'),
            'email'        => session()->get('email'),
            'waktu_login'  => session()->get('waktu_login'),
            'status_login' => session()->get('status_login'),
        ];

        return view('v_profil', $data);
    }
}
