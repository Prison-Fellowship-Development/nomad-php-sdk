<?php

namespace PrisonFellowship\NomadPHPSDK\Requests\ContentManager;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GetFeaturedContentRequest extends Request implements Hasbody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(protected string $token, protected array $returnedFieldNames)
    {
    }

    public function defaultHeaders(): array
    {
        return [
            'Authorization' => 'Bearer '.$this->token,
            'Content-Type' => 'application/json',
        ];
    }

    public function defaultBody(): array
    {
        return [
            'returnedFieldNames' => $this->returnedFieldNames,
        ];
    }

    public function resolveEndpoint(): string
    {
        return "/api/portal/featured-content";
    }
}