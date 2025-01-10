<?php

declare(strict_types=1);

namespace PrisonFellowship\NomadPHPSDK\Requests\ContentTracking;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class AddUserContentAttributeRequest extends Request implements HasBody
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

    public function defaultHeaders(): array
    {
        return [
            'Authorization' => 'Bearer '.$this->token,
            'Content-Type' => 'application/json',
        ];
    }

    public function resolveEndpoint(): string
    {
        return '/portal/userContentAttribute/add';
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
