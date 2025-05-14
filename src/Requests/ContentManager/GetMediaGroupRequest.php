<?php

declare(strict_types=1);

namespace PrisonFellowship\NomadPHPSDK\Requests\ContentManager;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasStringBody;

class GetMediaGroupRequest extends Request implements Hasbody
{
    use HasStringBody;

    protected Method $method = Method::GET;

    public function __construct(protected string $groupId, protected string|array|null $filterIds = null)
    {
    }

    public function resolveEndpoint(): string
    {
        $query = '';

        if (!empty($this->filterIds)) {
            $filters = $this->normalizeFilterIds($this->filterIds);
            $query = '?' . implode('&', array_map(fn($id) => 'filterIds=' . urlencode($id), $filters));
        }

        return "/api/media/group/{$this->groupId}{$query}";
    }


    /**
     * Normalize the filterIds input to an array of strings
     *
     * @param string|array<string>|null $value
     * @return array<string>
     */
    private function normalizeFilterIds(string|array|null $value): array
    {
        return match (true) {
            is_array($value) => array_map('strval', $value),
            is_string($value) => [$value],
            default => [],
        };
    }
}
