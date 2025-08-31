<?php

namespace App\Libraries;

class SessionManager
{
    const ADMIN_SESSION_PREFIX = 'admin_';
    const USER_SESSION_PREFIX = 'user_';
    
    /**
     * Set admin session data
     */
    public static function setAdminSession($userData)
    {
        $adminData = [
            self::ADMIN_SESSION_PREFIX . 'logged_in' => true,
            self::ADMIN_SESSION_PREFIX . 'id' => $userData['user_id'],
            self::ADMIN_SESSION_PREFIX . 'email' => $userData['Email'],
            self::ADMIN_SESSION_PREFIX . 'username' => $userData['Username'],
            self::ADMIN_SESSION_PREFIX . 'role' => 'admin',
            self::ADMIN_SESSION_PREFIX . 'login_time' => time(),
            self::ADMIN_SESSION_PREFIX . 'session_type' => 'admin'
        ];
        
        session()->set($adminData);
        
        // Set cookie khusus admin untuk path /admin/*
        setcookie('admin_session', session()->session_id, [
            'expires' => time() + 86400,
            'path' => '/admin/',
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
        
        log_message('debug', 'Admin session set: ' . print_r($adminData, true));
    }
    
    /**
     * Set user session data
     */
    public static function setUserSession($userData)
    {
        $userData = [
            self::USER_SESSION_PREFIX . 'logged_in' => true,
            self::USER_SESSION_PREFIX . 'id' => $userData['user_id'],
            self::USER_SESSION_PREFIX . 'email' => $userData['Email'],
            self::USER_SESSION_PREFIX . 'username' => $userData['Username'],
            self::USER_SESSION_PREFIX . 'role' => 'user',
            self::USER_SESSION_PREFIX . 'login_time' => time(),
            self::USER_SESSION_PREFIX . 'session_type' => 'user',
            // Keep backward compatibility
            'isLoggedIn' => true,
            'user_id' => $userData['user_id'],
            'userEmail' => $userData['Email'],
            'username' => $userData['Username'],
            'role' => 'user'
        ];
        
        session()->set($userData);
        
        // Set cookie khusus user untuk path selain /admin/
        setcookie('user_session', session()->session_id, [
            'expires' => time() + 86400,
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
        
        log_message('debug', 'User session set: ' . print_r($userData, true));
    }
    
    /**
     * Check if admin is logged in
     */
    public static function isAdminLoggedIn()
    {
        $isLoggedIn = session()->get(self::ADMIN_SESSION_PREFIX . 'logged_in');
        $role = session()->get(self::ADMIN_SESSION_PREFIX . 'role');
        $loginTime = session()->get(self::ADMIN_SESSION_PREFIX . 'login_time');
        
        // Check timeout
        if ($loginTime && (time() - $loginTime) > 86400) {
            self::clearAdminSession();
            return false;
        }
        
        return $isLoggedIn === true && $role === 'admin';
    }
    
    /**
     * Check if user is logged in
     */
    public static function isUserLoggedIn()
    {
        $isLoggedIn = session()->get(self::USER_SESSION_PREFIX . 'logged_in') || session()->get('isLoggedIn');
        $role = session()->get(self::USER_SESSION_PREFIX . 'role') || session()->get('role');
        $loginTime = session()->get(self::USER_SESSION_PREFIX . 'login_time') || session()->get('login_time');
        
        // Check timeout
        if ($loginTime && (time() - $loginTime) > 86400) {
            self::clearUserSession();
            return false;
        }
        
        return $isLoggedIn === true && $role === 'user';
    }
    
    /**
     * Get admin data
     */
    public static function getAdminData()
    {
        if (!self::isAdminLoggedIn()) {
            return null;
        }
        
        return [
            'id' => session()->get(self::ADMIN_SESSION_PREFIX . 'id'),
            'email' => session()->get(self::ADMIN_SESSION_PREFIX . 'email'),
            'username' => session()->get(self::ADMIN_SESSION_PREFIX . 'username'),
            'role' => session()->get(self::ADMIN_SESSION_PREFIX . 'role'),
            'login_time' => session()->get(self::ADMIN_SESSION_PREFIX . 'login_time')
        ];
    }
    
    /**
     * Get user data
     */
    public static function getUserData()
    {
        if (!self::isUserLoggedIn()) {
            return null;
        }
        
        return [
            'id' => session()->get(self::USER_SESSION_PREFIX . 'id') ?: session()->get('user_id'),
            'email' => session()->get(self::USER_SESSION_PREFIX . 'email') ?: session()->get('userEmail'),
            'username' => session()->get(self::USER_SESSION_PREFIX . 'username') ?: session()->get('username'),
            'role' => session()->get(self::USER_SESSION_PREFIX . 'role') ?: session()->get('role'),
            'login_time' => session()->get(self::USER_SESSION_PREFIX . 'login_time') ?: session()->get('login_time')
        ];
    }
    
    /**
     * Clear admin session
     */
    public static function clearAdminSession()
    {
        $adminKeys = [
            self::ADMIN_SESSION_PREFIX . 'logged_in',
            self::ADMIN_SESSION_PREFIX . 'id',
            self::ADMIN_SESSION_PREFIX . 'email',
            self::ADMIN_SESSION_PREFIX . 'username',
            self::ADMIN_SESSION_PREFIX . 'role',
            self::ADMIN_SESSION_PREFIX . 'login_time',
            self::ADMIN_SESSION_PREFIX . 'session_type'
        ];
        
        session()->remove($adminKeys);
        
        // Clear admin cookie
        setcookie('admin_session', '', [
            'expires' => time() - 3600,
            'path' => '/admin/',
            'httponly' => true
        ]);
        
        log_message('debug', 'Admin session cleared');
    }
    
    /**
     * Clear user session
     */
    public static function clearUserSession()
    {
        $userKeys = [
            self::USER_SESSION_PREFIX . 'logged_in',
            self::USER_SESSION_PREFIX . 'id',
            self::USER_SESSION_PREFIX . 'email',
            self::USER_SESSION_PREFIX . 'username',
            self::USER_SESSION_PREFIX . 'role',
            self::USER_SESSION_PREFIX . 'login_time',
            self::USER_SESSION_PREFIX . 'session_type',
            // Backward compatibility
            'isLoggedIn',
            'user_id',
            'userEmail',
            'username',
            'role',
            'login_time'
        ];
        
        session()->remove($userKeys);
        
        // Clear user cookie
        setcookie('user_session', '', [
            'expires' => time() - 3600,
            'path' => '/',
            'httponly' => true
        ]);
        
        log_message('debug', 'User session cleared');
    }
    
    /**
     * Get current context based on URL
     */
    public static function getCurrentContext()
    {
        $uri = service('request')->getUri();
        $path = $uri->getPath();
        
        if (strpos($path, '/admin') === 0) {
            return 'admin';
        }
        
        return 'user';
    }
    
    /**
     * Smart session check based on context
     */
    public static function isLoggedInForContext($context = null)
    {
        if ($context === null) {
            $context = self::getCurrentContext();
        }
        
        if ($context === 'admin') {
            return self::isAdminLoggedIn();
        } else {
            return self::isUserLoggedIn();
        }
    }
    
    /**
     * Extend session time
     */
    public static function extendSession($context = null)
    {
        if ($context === null) {
            $context = self::getCurrentContext();
        }
        
        $currentTime = time();
        
        if ($context === 'admin' && self::isAdminLoggedIn()) {
            session()->set(self::ADMIN_SESSION_PREFIX . 'login_time', $currentTime);
        } elseif ($context === 'user' && self::isUserLoggedIn()) {
            session()->set(self::USER_SESSION_PREFIX . 'login_time', $currentTime);
            session()->set('login_time', $currentTime); // Backward compatibility
        }
    }
    
    /**
     * Debug info for all sessions
     */
    public static function getDebugInfo()
    {
        return [
            'admin_session' => [
                'logged_in' => self::isAdminLoggedIn(),
                'data' => self::getAdminData()
            ],
            'user_session' => [
                'logged_in' => self::isUserLoggedIn(),
                'data' => self::getUserData()
            ],
            'current_context' => self::getCurrentContext(),
            'all_session_data' => session()->get()
        ];
    }
}