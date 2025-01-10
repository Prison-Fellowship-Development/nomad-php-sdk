<?php

declare(strict_types=1);

namespace PrisonFellowship\NomadPHPSDK\Requests\ContentManager;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasStringBody;

class GetMyContentRequest extends Request implements Hasbody
{
    use HasStringBody;

    protected Method $method = Method::GET;

    public function __construct(protected string $token, protected string $contentId)
    {
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
        return "/api/media/my-content/{$this->contentId}";
    }
}
