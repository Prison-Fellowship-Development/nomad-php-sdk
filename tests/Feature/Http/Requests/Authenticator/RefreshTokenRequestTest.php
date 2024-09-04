<?php

declare(strict_types=1);

use PrisonFellowship\NomadPHPSDK\NomadMediaConnector;
use PrisonFellowship\NomadPHPSDK\Requests\Authenticator\RefreshTokenRequest;
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

it('sends the correct refresh token request', function () {
    $this->mockClient->addResponse(MockResponse::make(['token' => 'new_fake_token'], 200));

    $token = 'fake_token';
    $refreshToken = 'fake_refresh_token';
    $refreshTokenRequest = new RefreshTokenRequest($token, $refreshToken);
    $response = $this->connector->send($refreshTokenRequest);

    expect($refreshTokenRequest->getMethod())->toBe(Method::POST);
    expect($refreshTokenRequest->resolveEndpoint())->toBe('/api/account/refresh-token');
    expect($refreshTokenRequest->defaultHeaders())->toBe([
        'Authorization' => "Bearer {$token}",
        'Content-Type' => 'application/json',
    ]);
    expect($refreshTokenRequest->defaultBody())->toBe([
        'refreshToken' => $refreshToken,
    ]);
    expect($response->status())->toBe(200);
    expect($response->json('token'))->toBe('new_fake_token');
});

it('handles expired refresh token', function () {
    $this->mockClient->addResponse(MockResponse::make(['error' => 'Token expired'], 401));

    $token = 'fake_token';
    $refreshToken = 'expired_refresh_token';
    $refreshTokenRequest = new RefreshTokenRequest($token, $refreshToken);
    $response = $this->connector->send($refreshTokenRequest);

    expect($response->status())->toBe(401);
    expect($response->json('error'))->toBe('Token expired');
});

it('handles empty responses in refresh token', function () {
    $this->mockClient->addResponse(MockResponse::make([], 200));

    $token = 'fake_token';
    $refreshToken = 'some_refresh_token';
    $refreshTokenRequest = new RefreshTokenRequest($token, $refreshToken);
    $response = $this->connector->send($refreshTokenRequest);

    expect($response->status())->toBe(200);
    expect($response->json())->toBeEmpty();
});
