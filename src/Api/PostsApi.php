<?php

namespace Sevaske\LaravelDiscourse\Api;

class PostsApi extends ApiService
{
    public function latest(?int $before = null)
    {
        $params = $before === null ? [] : ['before' => $before];

        return $this->get('/posts.json', $params);
    }

    public function find($id)
    {
        return $this->get("/posts/{$id}.json");
    }
}
