<?php

declare(strict_types=1);

namespace PrisonFellowship\NomadPHPSDK\Requests\Utility;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class ForgotPasswordRequest extends Request
{
    protected Method $method = Method::POST;

    public function __construct(protected string $username)
    {
    }

    public function resolveEndpoint(): string
    {
        return '/api/account/forgot-password';
    }

    public function defaultBody(): array
    {
        return [
            'username' => $this->username,
        ];
    }
}
