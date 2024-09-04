<?php

declare(strict_types=1);

use PrisonFellowship\NomadPHPSDK\NomadMediaConnector;
use PrisonFellowship\NomadPHPSDK\Requests\Authenticator\LogoutRequest;
use Saloon\Enums\Method;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    $this->mockClient = new MockClient();
    $this->connector = new NomadMediaConnector([
        'serviceApiUrl' => 'https://api.example.com',
    ]);
    $this->connector->withMockClient($this->mockClient);
});

it('sends the correct logout request', function () {
    $this->mockClient->addResponse(MockResponse::make([], 200));

    $token = 'fake_token';
    $userSessionId = 'fake_user_session_id';
    $logoutRequest = new LogoutRequest($token, $userSessionId);
    $response = $this->connector->send($logoutRequest);

    expect($logoutRequest->getMethod())->toBe(Method::POST);
    expect($logoutRequest->resolveEndpoint())->toBe('/api/account/logout');
    expect($logoutRequest->defaultHeaders())->toBe([
        'Authorization' => "Bearer {$token}",
        'Content-Type' => 'application/json',
    ]);
    expect($response->status())->toBe(200);
});

it('handles missing token in logout request', function () {
    $this->mockClient->addResponse(MockResponse::make(['error' => 'Unauthorized'], 401));

    $logoutRequest = new LogoutRequest('', '');
    $response = $this->connector->send($logoutRequest);

    expect($response->status())->toBe(401);
    expect($response->json('error'))->toBe('Unauthorized');
});
