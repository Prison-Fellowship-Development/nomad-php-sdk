<?php

declare(strict_types=1);

namespace PrisonFellowship\NomadPHPSDK\Requests\Utility;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class ForgotPasswordRequest extends Request implements Hasbody
{
    use HasJsonBody;

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
