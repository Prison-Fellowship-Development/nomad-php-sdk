<?php

declare(strict_types=1);

namespace PrisonFellowship\NomadPHPSDK\Requests\ContentManager;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class MediaSearchRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected array $searchParams)
    {
    }

    public function resolveEndpoint(): string
    {
        return '/api/media/search';
    }

    public function defaultQuery(): array
    {
        return $this->searchParams;
    }
}
