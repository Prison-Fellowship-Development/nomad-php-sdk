<?php

declare(strict_types=1);

namespace PrisonFellowship\NomadPHPSDK\Requests\Authenticator;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasStringBody;

class GetOrCreateProfileRequest extends Request implements Hasbody
{
    use HasStringBody;

    protected Method $method = Method::GET;

    public function __construct(protected string $token, protected string $userId)
    {
    }

    public function defaultHeaders(): array
    {
        return [
            'Authorization' => 'Bearer '.$this->token,
            'Content-Type' => 'application/json',
        ];
    }

    public function resolveEndpoint(): string
    {
        return "/portal/profile/{$this->userId}";
    }
}
