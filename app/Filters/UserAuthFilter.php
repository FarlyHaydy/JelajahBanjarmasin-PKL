<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class UserAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Get current session using AuthController method
        $authController = new \App\Controllers\AuthController();
        $currentSession = $authController->getCurrentSession();
        
        // Cek apakah ada session aktif
        if (!$currentSession) {
            return redirect()->to('/login')->with('error', 'Anda harus login untuk mengakses halaman ini');
        }
        
        // Session valid, lanjutkan (user bisa mengakses user area)
        log_message('debug', 'User access granted for session: ' . $currentSession['session_id']);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}