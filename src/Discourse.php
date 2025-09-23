<?php

namespace Sevaske\LaravelDiscourse;

use Closure;
use Illuminate\Support\Traits\Macroable;
use Sevaske\Discourse\Exceptions\DiscourseException;
use Sevaske\Discourse\Services\Api;
use Sevaske\Discourse\Services\Connect\RequestPayload;
use Sevaske\Discourse\Services\Connect\ResponsePayload;
use Sevaske\Discourse\Services\Signer;
use Sevaske\LaravelDiscourse\Contracts\DiscourseUser;

class Discourse
{
    use Macroable;

    protected ?Api $api = null;

    public function __construct(protected Signer $signer, protected Closure $apiFactory) {}

    public function signer(): Signer
    {
        return $this->signer;
    }

    public function api(): Api
    {
        if ($this->api === null) {
            $this->api = ($this->apiFactory)();
        }

        return $this->api;
    }

    /**
     * @throws DiscourseException
     */
    public function connect(string $sso, DiscourseUser|array $user): string
    {
        if ($user instanceof DiscourseUser) {
            $userId = $user->getId();
            $email = $user->getEmail();
            $extra = [
                'username' => $user->getUsername(),
                'name' => $user->getFullName(),
                'avatar_url' => $user->getAvatarUrl(),
                'bio' => $user->getBio(),
                'admin' => $user->isDiscourseAdmin(),
                'moderator' => $user->isDiscourseModerator(),
            ];
        } else {
            if (! empty($user['id']) || ! empty($user['email'])) {
                throw new DiscourseException('You must provide a valid user ID and email.');
            }

            $userId = $user['id'];
            $email = $user['email'];
            unset($user['id'], $user['email']);
            $extra = $user;
        }

        $requestPayload = new RequestPayload($sso);
        $response = (new ResponsePayload($this->signer()))->build(
            $requestPayload->nonce(),
            $userId,
            $email,
            array_filter($extra, function ($value) {
                return $value !== null;
            })
        );

        return $requestPayload->buildReturnUrl($response);
    }
}
