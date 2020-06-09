<?php

namespace App\Core\View;

class AuthExtension
{
    protected $auth;

    protected $permissions;

    public function __construct($auth)
    {
        $this->auth = $auth;

        if ($auth) {
            $roles = require BASE_DIR . '/config/roles.php';

            $this->permissions = $roles[$auth['role_id']]['permissions'];
        }
    }

    public function hasPermission(string $permission): bool
    {
        if (in_array($permission, $this->permissions)) {
            return true;
        }

        return false;
    }

    public function isLogged(): bool
    {
        if ($this->auth) {
            return true;
        }

        return false;
    }
}
