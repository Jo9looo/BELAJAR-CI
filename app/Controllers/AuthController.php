<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\UserModel;

class AuthController extends BaseController
{
    protected $UserModel;

    function __construct()
    {   
        helper('form');
        $this->userModel = new UserModel();
    }
public function login()
{
    if ($this->request->getPost()) {
        $rules = [
            'username' => 'required|min_length[6]',
            'password' => 'required|min_length[7]|numeric',
        ];


    if ($this->validate($rules)) {
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $dataUser = $this->userModel->where(['username' => $username])->first();

        if ($dataUser) {

            if (password_verify($password, $dataUser['password'])) {
                // ni buat ngatur waktu
                date_default_timezone_set('Asia/Jakarta');

                session()->set([
                    'username' => $dataUser['username'],
                    'role' => $dataUser['role'],
                    'email' => $dataUser['email'],
                    'waktu_login' => date('Y-m-d H:i:s'),   
                    'status_login' => 'Sudah Login', 
                    'isLoggedIn' => TRUE
                ]);

                return redirect()->to(base_url('/'));
            } else {
                session()->setFlashdata('failed', 'Username & Password Salah');
                return redirect()->back();
            }
        } else {
            session()->setFlashdata('failed', 'Username Tidak Ditemukan');
            return redirect()->back();
        }

        } else {
        session()->setFlashdata('failed', $this->validator->listErrors());
        return redirect()->back();
        }

    } else {
        return view('v_login');
    }
}

public function logout()
{
    // Hapus semua data session secara spesifik lalu hancurkan
    session()->remove(['username', 'role', 'email', 'waktu_login', 'status_login', 'isLoggedIn']);
    session()->destroy();
    
    return redirect()->to('login');
}
}
