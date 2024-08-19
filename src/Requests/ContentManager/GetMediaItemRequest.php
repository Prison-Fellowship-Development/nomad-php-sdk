<?php

declare(strict_types=1);

namespace PrisonFellowship\NomadPHPSDK\Requests\ContentManager;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetMediaItemRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected string $mediaItemId)
    {
    }

    public function resolveEndpoint(): string
    {
        return "/api/media/item/{$this->mediaItemId}";
    }
}
