<?php

namespace Sevaske\LaravelDiscourse\Contracts;

interface DiscourseUser
{
    /**
     * Unique external ID for Discourse SSO. This must never change.
     */
    public function getId(): string;

    /**
     * Verified email address of the user.
     * If the email is not verified, "require_activation" should be set on the payload.
     */
    public function getEmail(): string;

    /**
     * Username for Discourse.
     */
    public function getUsername(): ?string;

    /**
     * Full display name for the user on Discourse.
     */
    public function getFullName(): ?string;

    /**
     * Avatar image URL for the user.
     */
    public function getAvatarUrl(): ?string;

    /**
     * User biography text.
     */
    public function getBio(): ?string;

    /**
     * Whether the user should be granted Discourse admin rights.
     */
    public function isDiscourseAdmin(): bool;

    /**
     * Whether the user should be granted Discourse moderator rights.
     */
    public function isDiscourseModerator(): bool;
}
