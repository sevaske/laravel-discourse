<?php

namespace Sevaske\LaravelDiscourse\Api;

class AuthApi extends ApiService
{
    public function logout($userId): bool
    {
        $response = $this->post("/admin/users/{$userId}/log_out.json");

        return ($response['success'] ?? null) === 'OK';
    }
}
