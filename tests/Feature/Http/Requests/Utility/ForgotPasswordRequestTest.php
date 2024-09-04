<?php

declare(strict_types=1);

use PrisonFellowship\NomadPHPSDK\NomadMediaConnector;
use PrisonFellowship\NomadPHPSDK\Requests\Utility\ForgotPasswordRequest;
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

it('sends the correct forgot password request', function () {
    $this->mockClient->addResponse(MockResponse::make(['message' => 'Password reset link sent'], 200));

    $username = 'test_user';
    $forgotPasswordRequest = new ForgotPasswordRequest($username);
    $response = $this->connector->send($forgotPasswordRequest);

    expect($forgotPasswordRequest->getMethod())->toBe(Method::POST);
    expect($forgotPasswordRequest->resolveEndpoint())->toBe('/api/account/forgot-password');
    expect($forgotPasswordRequest->defaultBody())->toBe([
        'username' => $username,
    ]);
    expect($response->status())->toBe(200);
    expect($response->json('message'))->toBe('Password reset link sent');
});

it('handles invalid username in forgot password request', function () {
    $this->mockClient->addResponse(MockResponse::make(['error' => 'User not found'], 404));

    $username = 'non_existent_user';
    $forgotPasswordRequest = new ForgotPasswordRequest($username);
    $response = $this->connector->send($forgotPasswordRequest);

    expect($response->status())->toBe(404);
    expect($response->json('error'))->toBe('User not found');
});

it('handles empty responses in forgot password request', function () {
    $this->mockClient->addResponse(MockResponse::make([], 200));

    $username = 'user_with_empty_response';
    $forgotPasswordRequest = new ForgotPasswordRequest($username);
    $response = $this->connector->send($forgotPasswordRequest);

    expect($response->status())->toBe(200);
    expect($response->json())->toBeEmpty();
});
