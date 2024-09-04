<?php

declare(strict_types=1);

use PrisonFellowship\NomadPHPSDK\NomadMediaConnector;
use PrisonFellowship\NomadPHPSDK\Requests\Utility\ResetPasswordRequest;
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

it('sends the correct reset password request', function () {
    $this->mockClient->addResponse(MockResponse::make(['message' => 'Password reset successful'], 200));

    $username = 'test_user';
    $token = 'valid_token';
    $newPassword = 'new_secure_password';

    $resetPasswordRequest = new ResetPasswordRequest($username, $token, $newPassword);
    $response = $this->connector->send($resetPasswordRequest);

    expect($resetPasswordRequest->getMethod())->toBe(Method::POST);
    expect($resetPasswordRequest->resolveEndpoint())->toBe('/api/account/reset-password');
    expect($resetPasswordRequest->defaultBody())->toBe([
        'userName' => $username,
        'TOKEN' => $token,
        'newPassword' => $newPassword,
    ]);
    expect($response->status())->toBe(200);
    expect($response->json('message'))->toBe('Password reset successful');
});

it('handles invalid token in reset password request', function () {
    $this->mockClient->addResponse(MockResponse::make(['error' => 'Invalid or expired token'], 401));

    $username = 'test_user';
    $token = 'invalid_token';
    $newPassword = 'new_secure_password';

    $resetPasswordRequest = new ResetPasswordRequest($username, $token, $newPassword);
    $response = $this->connector->send($resetPasswordRequest);

    expect($response->status())->toBe(401);
    expect($response->json('error'))->toBe('Invalid or expired token');
});

it('handles empty responses in reset password request', function () {
    $this->mockClient->addResponse(MockResponse::make([], 200));

    $username = 'test_user';
    $token = 'some_token';
    $newPassword = 'new_secure_password';

    $resetPasswordRequest = new ResetPasswordRequest($username, $token, $newPassword);
    $response = $this->connector->send($resetPasswordRequest);

    expect($response->status())->toBe(200);
    expect($response->json())->toBeEmpty();
});
