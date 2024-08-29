<?php

declare(strict_types=1);

namespace PrisonFellowship\NomadPHPSDK\Requests\Authenticator;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class RefreshTokenRequest extends Request implements Hasbody
{
    use HasJsonBody;

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
