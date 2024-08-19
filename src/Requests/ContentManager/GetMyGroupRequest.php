<?php

declare(strict_types=1);

namespace PrisonFellowship\NomadPHPSDK\Requests\ContentManager;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetMyGroupRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected string $groupId)
    {
    }

    public function resolveEndpoint(): string
    {
        return "/api/media/my-group/{$this->groupId}";
    }
}
