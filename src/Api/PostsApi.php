<?php

namespace Sevaske\LaravelDiscourse\Api;

use Sevaske\LaravelDiscourse\Contracts\DiscourseResponseContract;

class PostsApi extends ApiService
{
    public function latest(?int $before = null): DiscourseResponseContract
    {
        $params = $before === null ? [] : ['before' => $before];

        return $this->get('/posts.json', $params);
    }

    public function retrieve($id): DiscourseResponseContract
    {
        return $this->get("/posts/{$id}.json");
    }

    public function delete($id): DiscourseResponseContract
    {
        return parent::delete("/posts/{$id}.json");
    }

    public function locked($id, bool $locked): DiscourseResponseContract
    {
        return $this->put("/posts/{$id}/locked.json", [
            'locked' => $locked ? 'true' : 'false',
        ]);
    }

    public function lock($id): DiscourseResponseContract
    {
        return $this->locked($id, true);
    }

    public function unlock($id): DiscourseResponseContract
    {
        return $this->locked($id, false);
    }
}
