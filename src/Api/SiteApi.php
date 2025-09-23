<?php

namespace Sevaske\LaravelDiscourse\Api;

use Sevaske\LaravelDiscourse\Contracts\DiscourseResponseContract;

class SiteApi extends ApiService
{
    public function info()
    {
        return $this->get('/site.json');
    }

    public function basicInfo(): DiscourseResponseContract
    {
        return $this->get('/site/basic-info.json');
    }
}
