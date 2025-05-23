<?php

declare(strict_types=1);

namespace PrisonFellowship\NomadPHPSDK\Requests\ContentTracking;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class RemoveWatchingRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $token,
        protected string $assetId,
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
        return "/api/portal/remove-watching/{$this->assetId}?profileId={$this->profileId}";
    }
}
