<?php

namespace Sevaske\LaravelDiscourse;

use Illuminate\Support\Traits\Macroable;
use Sevaske\Discourse\Exceptions\DiscourseException;
use Sevaske\Discourse\Services\Connect\RequestPayload;
use Sevaske\Discourse\Services\Connect\ResponsePayload;
use Sevaske\Discourse\Services\Signer;
use Sevaske\LaravelDiscourse\Contracts\DiscourseUser;

class Discourse
{
    use Macroable;

    public function __construct(protected Signer $signer, protected Api $api) {}

    public function api(): Api
    {
        return $this->api;
    }

    /**
     * @throws DiscourseException
     */
    public function connect(DiscourseUser|array $user, string $sso): string
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
        $response = (new ResponsePayload($this->signer))->build(
            $requestPayload->nonce(),
            $userId,
            $email,
            array_filter($extra, function ($value) {
                return $value !== null;
            })
        );

        return $requestPayload->returnUrl().'?'.$response;
    }
}
