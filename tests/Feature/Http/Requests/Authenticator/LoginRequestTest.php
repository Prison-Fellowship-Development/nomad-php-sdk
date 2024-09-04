<?php

declare(strict_types=1);

use PrisonFellowship\NomadPHPSDK\NomadMediaConnector;
use PrisonFellowship\NomadPHPSDK\Requests\Authenticator\LoginRequest;
use Saloon\Enums\Method;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends the correct login request', function () {
    // Arrange
    $mockClient = new MockClient([
        MockResponse::make(['token' => 'fake_token'], 200),
    ]);

    $connector = new NomadMediaConnector([
        'serviceApiUrl' => 'https://api.example.com',
    ]);
    $connector->withMockClient($mockClient);

    $username = 'test_user';
    $password = 'test_password';

    $loginRequest = new LoginRequest($username, $password);

    // Act
    $response = $connector->send($loginRequest);

    // Assert
    expect($loginRequest->getMethod())->toBe(Method::POST);
    expect($loginRequest->resolveEndpoint())->toBe('/api/account/login');
    expect($loginRequest->defaultBody())->toBe([
        'userName' => $username,
        'password' => $password,
    ]);
    expect($response->status())->toBe(200);
    expect($response->json('token'))->toBe('fake_token');
});
