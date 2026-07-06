<?php

namespace App\Controllers\Api;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\ProductModel;

class ProdukController extends ResourceController
{
    protected $model;  
    private $token;

    public function __construct()
    { 
        $this->model = new ProductModel(); 
        $this->token = env('MY_API_KEY');
    }

    private function authenticate()
    {
        $header = $this->request->getHeaderLine('Authorization');

        if (empty($header)) {
            return false;
        }

        if (!preg_match('/Bearer\s+(.*)$/i', $header, $matches)) {
            return false;
        }

        return $matches[1] === $this->token;
    }

    private function unauthorized()
    {
        return $this->respond([
            'status'  => false,
            'message' => 'Unauthorized'
        ], 401);
    }

    public function index()
    {
        if (!$this->authenticate()) {
            return $this->unauthorized();
        }

        return $this->respond($this->model->findAll());
    }
}
