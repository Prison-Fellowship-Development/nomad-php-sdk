<?php

declare(strict_types=1);

namespace PrisonFellowship\NomadPHPSDK\Requests\ContentManager;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class CreateFormRequest extends Request
{
    protected Method $method = Method::POST;

    public function __construct(protected array $formData)
    {
    }

    public function resolveEndpoint(): string
    {
        return '/api/media/form';
    }

    public function defaultBody(): array
    {
        return $this->formData;
    }
}
