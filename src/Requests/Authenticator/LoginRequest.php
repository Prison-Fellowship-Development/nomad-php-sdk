<?php

declare(strict_types=1);

namespace PrisonFellowship\NomadPHPSDK\Requests\Authenticator;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class LoginRequest extends Request implements HasBody
{
    use HasJsonBody;

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
