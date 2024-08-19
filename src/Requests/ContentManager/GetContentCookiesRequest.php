<?php

declare(strict_types=1);

namespace PrisonFellowship\NomadPHPSDK\Requests\ContentManager;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetContentCookiesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected string $cookieId)
    {
    }

    public function resolveEndpoint(): string
    {
        return "/api/media/cookies/{$this->cookieId}";
    }
}
