<?php

namespace Sevaske\LaravelDiscourse\Api;

use Sevaske\LaravelDiscourse\Contracts\DiscourseResponseContract;

class AdminApi extends ApiService
{
    public function user(int $id): DiscourseResponseContract
    {
        return $this->request('GET', "/admin/users/{$id}.json");
    }

    public function deleteUser(
        int $id,
        ?bool $deletePosts = null,
        ?bool $blockEmail = null,
        ?bool $blockUrls = null,
        ?bool $blockIp = null
    ): DiscourseResponseContract {
        return $this->request('DELETE', "/admin/users/{$id}.json", [
            'delete_posts' => $deletePosts,
            'block_email' => $blockEmail,
            'block_urls' => $blockUrls,
            'block_ip' => $blockIp,
        ]);
    }

    public function activateUser(int $id): bool
    {
        return $this->request('PUT', "/admin/users/{$id}/activate.json")->success === 'OK';
    }

    public function deactivateUser(int $id): bool
    {
        return $this->request('PUT', "/admin/users/{$id}/deactivate.json")->success === 'OK';
    }

    public function logoutUser($id): bool
    {
        return $this->request('POST', "/admin/users/{$id}/log_out.json")->success === 'OK';
    }
}
