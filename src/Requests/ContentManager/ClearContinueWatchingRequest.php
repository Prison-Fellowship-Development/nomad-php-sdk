<?php

declare(strict_types=1);

namespace PrisonFellowship\NomadPHPSDK\Requests\ContentManager;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class ClearContinueWatchingRequest extends Request implements Hasbody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(protected string $userId, protected string $assetId)
    {
    }

    public function resolveEndpoint(): string
    {
        return "/api/media/clear-continue-watching?userId={$this->userId}&assetId={$this->assetId}";
    }
}
