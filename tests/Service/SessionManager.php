<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionManager
{
    private SessionInterface $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function get(string $key, $default = null)
    {
        return $this->session->get($key, $default);
    }

    public function set(string $key, $value)
    {
        $this->session->set($key, $value);
    }
}
