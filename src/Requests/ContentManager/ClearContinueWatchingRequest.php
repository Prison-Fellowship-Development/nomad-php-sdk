<?php

declare(strict_types=1);

namespace PrisonFellowship\NomadPHPSDK\Requests\ContentManager;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class ClearContinueWatchingRequest extends Request
{
    protected Method $method = Method::POST;

    public function __construct(protected string $userId, protected string $assetId)
    {
    }

    public function resolveEndpoint(): string
    {
        return "/api/media/clear-continue-watching?userId={$this->userId}&assetId={$this->assetId}";
    }
}
