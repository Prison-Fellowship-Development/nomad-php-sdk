<?php

declare(strict_types=1);

namespace PrisonFellowship\NomadPHPSDK\Requests\Authenticator;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class LogoutRequest extends Request
{
    protected Method $method = Method::POST;

    public function __construct(protected string $token)
    {
    }

    public function resolveEndpoint(): string
    {
        return '/api/account/logout';
    }

    public function defaultHeaders(): array
    {
        return [
            'Authorization' => 'Bearer '.$this->token,
            'Content-Type' => 'application/json',
        ];
    }
}
