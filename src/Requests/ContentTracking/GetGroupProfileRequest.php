<?php

namespace PrisonFellowship\NomadPHPSDK\Requests\ContentTracking;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasStringBody;

class GetGroupProfileRequest extends Request implements Hasbody
{
    use HasStringBody;

    protected Method $method = Method::GET;


    public function __construct(
        protected string $token,
        protected string $groupId,
        protected string $profileId,
    ) {
    }

    protected function defaultHeaders(): array
    {
        return [
            'Authorization' => 'Bearer '.$this->token,
            'Content-Type' => 'application/json',
        ];
    }

    public function resolveEndpoint(): string
    {
        return "/api/media/my-group/{$this->groupId}/{$this->profileId}";
    }
}