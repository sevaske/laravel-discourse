<?php

namespace Sevaske\LaravelDiscourse\Api;

use Sevaske\LaravelDiscourse\Contracts\DiscourseResponseContract;

class UsersApi extends ApiService
{
    public function retrieve(string $usernameOrExternalId, bool $byExternalId = false): DiscourseResponseContract
    {
        if ($byExternalId) {
            return $this->request('GET', "/u/by-external/{$usernameOrExternalId}.json");
        }

        return $this->request('GET', "/u/{$usernameOrExternalId}.json");
    }

    public function create(string $name, string $email, string $password, string $username, array $extra = []): DiscourseResponseContract
    {
        return $this->request('POST', '/users.json', [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'username' => $username,
            ...$extra,
        ]);
    }

    public function update(string $username, string $name, array $extra): DiscourseResponseContract
    {
        return $this->request('PUT', '/u/'.$username.'.json', [
            'name' => $name,
            ...$extra,
        ]);
    }
}
