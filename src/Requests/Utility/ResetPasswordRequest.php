<?php

declare(strict_types=1);

namespace PrisonFellowship\NomadPHPSDK\Requests\Utility;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class ResetPasswordRequest extends Request
{
    protected Method $method = Method::POST;

    public function __construct(
        protected string $username,
        protected string $token,
        protected string $newPassword
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/api/account/reset-password';
    }

    public function defaultBody(): array
    {
        return [
            'userName' => $this->username,
            'TOKEN' => $this->token,
            'newPassword' => $this->newPassword,
        ];
    }
}
