<?php

declare(strict_types=1);

namespace PrisonFellowship\NomadPHPSDK\Requests\ContentTracking;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class RemoveUserContentAttributeRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $token,
        protected string $contentId,
        protected string $contentAttribute,
        protected string $profileId
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/portal/userContentAttribute/remove';
    }

    protected function defaultHeaders(): array
    {
        return [
            'Authorization' => 'Bearer '.$this->token,
            'Content-Type' => 'application/json',
        ];
    }

    protected function defaultBody(): array
    {
        return [
            'contentId' => $this->contentId,
            'contentAttribute' => $this->contentAttribute,
            'profileId' => $this->profileId,
        ];
    }
}
