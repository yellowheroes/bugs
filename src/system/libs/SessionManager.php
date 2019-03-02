<?php
/*
 * class SessionManager is inspired by: http://blog.teamtreehouse.com/how-to-create-bulletproof-sessions
 * - function 'migrate' is heavily based off of static function regenerateSession()
 */
namespace yellowheroes\jimmy\system\libs;

use yellowheroes\jimmy\system\config as config;

class SessionManager
{

    public $status = null;
    public $sessionConfig = [];

    public function __construct()
    {
        $this->getConfig(); // before session start, set session configuration
    }

    /**
     * get the session configuration settings
     * and store them in $sessionConfig
     * 
     * @return array $this->sessionConfig
     */
    public function getConfig(): array
    {
        $this->sessionConfig = (new config\Config())->getSessionConfig();
        return $this->sessionConfig;
    }

    /**
     * start a new session or resume an active session
     * @param int $interval   defaults to 300 - set to 0 to regenerate a new session id directly (see LoginModel)
     */
    public function start($interval = 300)
    {
        $status = $this->getStatus();
        if($status === PHP_SESSION_NONE) {
        \session_start($this->sessionConfig);
        $this->migrate($interval); // refresh session ID every $interval seconds (if set to 0, regenerate immediately)
        $this->set('session-ID', $this->getId());
        return true;
        } else {
            return false;
        }
    }

    /**
     * migrate the session to a new ID (reference index for session variables)
     * default interval is set at 300 seconds - i.e. regenerate ID every 5 minutes
     * call migrate(0) to regenerate ID immediately (e.g. for escalating access privileges)
     */
    public function migrate($interval)
    {   
        // first invocation of SessionManager::migrate()
        // - regenerate session ID
        // - and initiate $_SESSION['valid'] with timestamp
        if (!isset($_SESSION['valid'])) {
            \session_regenerate_id(true); // true == delete old session, don't leave a trail
            $this->set('valid', \time()); // set timestamp (at which session started)
            //$_SESSION['valid'] = \time(); // set timestamp
        }
        
        // subsequent invocations of SessionManager::migrate()
        // - check if session is still valid
        // if:  $this->get('valid') < time() - $interval === true,
        //      then the session is no longer valid (expired) and needs a new ID
        if ($this->get('valid') < \time() - $interval) {
            \session_regenerate_id(true); // true == delete old session, don't leave a trail
            $this->set('valid', \time()); // reset timestamp
        }
        
        return;
    }

    public function set($key = null, $value = null)
    {
        $_SESSION[$key] = $value;
        return;
    }

    public function get($key)
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        } else {
            return false;
        }
    }
    
    public function remove($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
            return true;
        } else {
            return false;
        }
    }
    
    public function clear()
    {
        $_SESSION = [];
        return true;
    }
    
    public function deleteCookie()
    {
        if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]);
        }
    }

    /**
     * Return value of PHP session_status()
     * PHP_SESSION_DISABLED = 0         sessions are disabled.
     * PHP_SESSION_NONE = 1             sessions are enabled, but none exists
     * PHP_SESSION_ACTIVE = 2           sessions are enabled, and one exists
     */
    public function getStatus()
    {
        $this->status = (\session_status() === \PHP_SESSION_NONE) ? 1 : (\session_status() === \PHP_SESSION_ACTIVE ? 2 : 0);
        return $this->status;
    }

    public function getId()
    {
        $this->sessId = \session_id();
        return $this->sessId;
    }
    
    public function dump()
    {
        echo '<pre>';
        var_dump($_SESSION);
        echo '</pre>';
    }
}
