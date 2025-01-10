<?php

declare(strict_types=1);

namespace PrisonFellowship\NomadPHPSDK\Requests\ContentTracking;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasStringBody;

class GetVideoTrackingRequest extends Request implements Hasbody
{
    use HasStringBody;

    protected Method $method = Method::GET;

    public function __construct(
        protected string $token,
        protected string $assetId,
        protected string $profileId,
        protected int $second,
        protected ?int $trackingEvent = null,
    ) {
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
        return '/asset/tracking';
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'assetId' => $this->assetId,
            'trackingEvent' => $this->trackingEvent,
            'second' => $this->second,
            'profileId' => $this->profileId,
        ], fn ($value) => $value !== null);
    }
}
