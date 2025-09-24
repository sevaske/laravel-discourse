<?php

namespace Sevaske\LaravelDiscourse\Services;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use Sevaske\Discourse\Exceptions\DiscourseException;
use Sevaske\Discourse\Exceptions\InvalidArgumentException;
use Sevaske\Discourse\Services\Connect\RequestPayload;
use Sevaske\Discourse\Services\Connect\ResponsePayload;
use Sevaske\Discourse\Services\Signer;
use Sevaske\LaravelDiscourse\Contracts\DiscourseUser;

class SsoService
{
    public function __construct(protected Signer $signer) {}

    /**
     * @throws DiscourseException
     */
    public function connect(string $sso, Authenticatable|DiscourseUser|Collection|array $user): string
    {
        $user = $this->normalizeUser($user);

        $requestPayload = new RequestPayload($sso);
        $response = (new ResponsePayload($this->signer))->build(
            $requestPayload->nonce(),
            $user['id'],
            $user['email'],
            $user->except(['id', 'email'])->toArray(),
        );

        return $requestPayload->buildReturnUrl($response);
    }

    /**
     * Normalize user to Discourse SSO format
     *
     *
     *
     * @throws InvalidArgumentException
     */
    public function normalizeUser(Authenticatable|DiscourseUser|Collection|array $user): Collection
    {
        if ($user instanceof DiscourseUser) {
            $user = collect([
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'username' => $user->getUsername(),
                'name' => $user->getFullName(),
                'avatar_url' => $user->getAvatarUrl(),
                'bio' => $user->getBio(),
                'admin' => $user->isDiscourseAdmin(),
                'moderator' => $user->isDiscourseModerator(),
            ]);
        } elseif ($user instanceof Authenticatable) {
            $user = collect([
                'id' => $user->{config('discourse.sso.user.id')},
                'email' => $user->{config('discourse.sso.user.email')},
                'username' => $user->{config('discourse.sso.user.username')},
                'name' => $user->{config('discourse.sso.user.name')},
                'avatar_url' => $user->{config('discourse.sso.user.avatar_url')},
                'bio' => $user->{config('discourse.sso.user.bio')},
                'admin' => $user->{config('discourse.sso.user.admin')},
                'moderator' => $user->{config('discourse.sso.user.moderator')},
            ]);
        } elseif (is_array($user)) {
            $user = collect($user);
        }

        // remove null values
        $user = $user->filter(fn ($value) => ! is_null($value));

        // require id & email
        if (! $user->has('id') || ! $user->has('email')) {
            throw new InvalidArgumentException('Discourse SSO requires both "id" and "email".');
        }

        return $user;
    }
}
