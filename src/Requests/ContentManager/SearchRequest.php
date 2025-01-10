<?php

declare(strict_types=1);

namespace PrisonFellowship\NomadPHPSDK\Requests\ContentManager;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Repositories\Body\JsonBodyRepository;
use Saloon\Traits\Body\HasJsonBody;

class SearchRequest extends Request implements Hasbody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(protected string $token)
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
        return '/api/portal/search';
    }

    /**
     * Default body for the request.
     *
     * @return array
     */
    protected function defaultBody(): array
    {
        return [
            'query' => null,
            'offset' => 0,
            'size' => 10,
            'filters' => [],
            'sortFields' => [],
            'searchResultFields' => [],
            'fullUrlFieldNames' => null,
            'distinctOnFieldName' => null,
            'includeVideoClips' => false,
            'similarAssetId' => null,
            'minScore' => null,
            'excludeTotalRecordCount' => false,
            'filterBinder' => null,
            'useLlmSearch' => false,
            'includeInternalFieldsInResults' => false,
        ];
    }

    /**
     * Allow customization of the body.
     *
     * @param array $body
     * @return $this
     */
    public function withBody(array $body): static
    {
        $this->body ??= new JsonBodyRepository(array_merge($this->defaultBody(), $body));

        return $this;
    }
}
