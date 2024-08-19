<?php

declare(strict_types=1);

namespace PrisonFellowship\NomadPHPSDK\Requests\Authenticator;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class LoginRequest extends Request
{
    protected Method $method = Method::POST;

    public function __construct(
        protected string $username,
        protected string $password
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/api/account/login';
    }

    public function defaultBody(): array
    {
        return [
            'userName' => $this->username,
            'password' => $this->password,
        ];
    }
}
