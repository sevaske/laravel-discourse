<?php

namespace Sevaske\LaravelDiscourse\Api;

class UsersApi extends ApiService
{
    public function find(string $usernameOrExternalId, bool $byExternalId = false)
    {
        if ($byExternalId) {
            return $this->get("/u/by-external/{$usernameOrExternalId}.json");
        }

        return $this->get("/u/{$usernameOrExternalId}.json");
    }

    public function create(string $name, string $email, string $password, string $username, array $extra = [])
    {
        return $this->post('/users.json', [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'username' => $username,
            ...$extra,
        ]);
    }

    public function update(string $username, string $name, array $extra)
    {
        return $this->put('/u/'.$username.'.json', [
            'name' => $name,
            ...$extra,
        ]);
    }
}
