<?php

declare(strict_types=1);

namespace PrisonFellowship\NomadPHPSDK\Requests\Authenticator;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class RefreshTokenRequest extends Request
{
    protected Method $method = Method::POST;

    public function __construct(protected string $refreshToken)
    {
    }

    public function resolveEndpoint(): string
    {
        return '/api/account/refresh-token';
    }

    public function defaultBody(): array
    {
        return [
            'refreshToken' => $this->refreshToken,
        ];
    }
}
