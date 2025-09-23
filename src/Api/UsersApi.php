<?php

namespace Sevaske\LaravelDiscourse\Api;

use Sevaske\LaravelDiscourse\Contracts\DiscourseResponseContract;

class UsersApi extends ApiService
{
    public function retrieve(string $usernameOrExternalId, bool $byExternalId = false): DiscourseResponseContract
    {
        if ($byExternalId) {
            return $this->get("/u/by-external/{$usernameOrExternalId}.json");
        }

        return $this->get("/u/{$usernameOrExternalId}.json");
    }

    public function create(string $name, string $email, string $password, string $username, array $extra = []): DiscourseResponseContract
    {
        return $this->post('/users.json', [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'username' => $username,
            ...$extra,
        ]);
    }

    public function update(string $username, string $name, array $extra): DiscourseResponseContract
    {
        return $this->put('/u/'.$username.'.json', [
            'name' => $name,
            ...$extra,
        ]);
    }
}
