<?php

// app/Libraries/CookieSessionManager.php
namespace App\Libraries;

class CookieSessionManager
{
    private $cookiePrefix = 'bjm_session_';
    private $cookieExpiry = 86400; // 24 hours
    
    public function __construct()
    {
        // Initialize
    }
    
    // Create session dengan cookie unique
    public function createSession($userData, $role)
    {
        $sessionId = uniqid('sess_' . $role . '_');
        $sessionData = [
            'session_id' => $sessionId,
            'user_id' => $userData['user_id'],
            'username' => $userData['Username'],
            'email' => $userData['Email'],
            'role' => $userData['Role'],
            'login_as' => $role,
            'login_time' => time(),
            'token' => bin2hex(random_bytes(32))
        ];
        
        // Set cookie dengan nama unik
        $cookieName = $this->cookiePrefix . $sessionId;
        $cookieValue = base64_encode(json_encode($sessionData));
        
        setcookie($cookieName, $cookieValue, time() + $this->cookieExpiry, '/', '', false, true);
        
        // Set session identifier untuk tracking cookie mana yang aktif
        session()->set('active_session_cookie', $cookieName);
        session()->set('active_session_id', $sessionId);
        
        return $sessionId;
    }
    
    // Get current session data dari cookie
    public function getCurrentSession()
    {
        $activeCookie = session()->get('active_session_cookie');
        
        if ($activeCookie && isset($_COOKIE[$activeCookie])) {
            $sessionData = json_decode(base64_decode($_COOKIE[$activeCookie]), true);
            
            // Validate session
            if ($this->isSessionValid($sessionData)) {
                return $sessionData;
            }
        }
        
        return null;
    }
    
    // Validate session
    private function isSessionValid($sessionData)
    {
        if (!$sessionData || !isset($sessionData['login_time'])) {
            return false;
        }
        
        // Check expiry
        if ((time() - $sessionData['login_time']) > $this->cookieExpiry) {
            return false;
        }
        
        return true;
    }
    
    // Get all active sessions
    public function getAllActiveSessions()
    {
        $sessions = [];
        
        foreach ($_COOKIE as $cookieName => $cookieValue) {
            if (strpos($cookieName, $this->cookiePrefix) === 0) {
                $sessionData = json_decode(base64_decode($cookieValue), true);
                if ($this->isSessionValid($sessionData)) {
                    $sessions[] = $sessionData;
                }
            }
        }
        
        return $sessions;
    }
    
    // Switch to different session
    public function switchToSession($sessionId)
    {
        $cookieName = $this->cookiePrefix . $sessionId;
        
        if (isset($_COOKIE[$cookieName])) {
            session()->set('active_session_cookie', $cookieName);
            session()->set('active_session_id', $sessionId);
            return true;
        }
        
        return false;
    }
    
    // Clear current session
    public function clearCurrentSession()
    {
        $activeCookie = session()->get('active_session_cookie');
        
        if ($activeCookie) {
            // Clear cookie
            setcookie($activeCookie, '', time() - 3600, '/');
            unset($_COOKIE[$activeCookie]);
        }
        
        // Clear session
        session()->remove(['active_session_cookie', 'active_session_id']);
    }
    
    // Clear all sessions
    public function clearAllSessions()
    {
        foreach ($_COOKIE as $cookieName => $cookieValue) {
            if (strpos($cookieName, $this->cookiePrefix) === 0) {
                setcookie($cookieName, '', time() - 3600, '/');
                unset($_COOKIE[$cookieName]);
            }
        }
        
        session()->remove(['active_session_cookie', 'active_session_id']);
    }
}
