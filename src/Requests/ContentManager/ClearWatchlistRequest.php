<?php

declare(strict_types=1);

namespace PrisonFellowship\NomadPHPSDK\Requests\ContentManager;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class ClearWatchlistRequest extends Request
{
    protected Method $method = Method::POST;

    public function __construct(protected string $userId)
    {
    }

    public function resolveEndpoint(): string
    {
        return "/api/media/clear-watchlist?userId={$this->userId}";
    }
}
