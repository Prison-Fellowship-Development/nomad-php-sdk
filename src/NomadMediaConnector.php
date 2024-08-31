<?php

declare(strict_types=1);

namespace PrisonFellowship\NomadPHPSDK;

use PrisonFellowship\NomadPHPSDK\Exceptions\NomadMediaException;
use PrisonFellowship\NomadPHPSDK\Requests\Authenticator\LoginRequest;
use PrisonFellowship\NomadPHPSDK\Requests\Authenticator\LogoutRequest;
use PrisonFellowship\NomadPHPSDK\Requests\Authenticator\RefreshTokenRequest;
use PrisonFellowship\NomadPHPSDK\Requests\ContentManager\ClearContinueWatchingRequest;
use PrisonFellowship\NomadPHPSDK\Requests\ContentManager\ClearWatchlistRequest;
use PrisonFellowship\NomadPHPSDK\Requests\ContentManager\CreateFormRequest;
use PrisonFellowship\NomadPHPSDK\Requests\ContentManager\GetContentCookiesRequest;
use PrisonFellowship\NomadPHPSDK\Requests\ContentManager\GetDefaultSiteConfigRequest;
use PrisonFellowship\NomadPHPSDK\Requests\ContentManager\GetDynamicContentsRequest;
use PrisonFellowship\NomadPHPSDK\Requests\ContentManager\GetMediaGroupRequest;
use PrisonFellowship\NomadPHPSDK\Requests\ContentManager\GetMediaItemRequest;
use PrisonFellowship\NomadPHPSDK\Requests\ContentManager\GetMyContentRequest;
use PrisonFellowship\NomadPHPSDK\Requests\ContentManager\GetMyGroupRequest;
use PrisonFellowship\NomadPHPSDK\Requests\ContentManager\GetSiteConfigRequest;
use PrisonFellowship\NomadPHPSDK\Requests\ContentManager\MediaSearchRequest;
use PrisonFellowship\NomadPHPSDK\Requests\Utility\ForgotPasswordRequest;
use PrisonFellowship\NomadPHPSDK\Requests\Utility\ResetPasswordRequest;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Connector;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Request;
use Saloon\Http\Response;

class NomadMediaConnector extends Connector
{
    protected ?string $token = null;

    protected string $serviceApiUrl;

    protected string $apiType;

    protected bool $debugMode;

    protected ?string $username;

    protected ?string $password;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        // Extract necessary configurations
        $this->serviceApiUrl = $config['serviceApiUrl'];
        $this->token = $config['token'] ?? null;
        $this->apiType = $config['apiType'] ?? 'portal';  // Default to 'portal'
        $this->debugMode = $config['debugMode'] ?? false;
        $this->username = $config['username'] ?? null;
        $this->password = $config['password'] ?? null;
    }

    public function resolveBaseUrl(): string
    {
        return $this->serviceApiUrl;
    }

    public function defaultHeaders(): array
    {
        $headers = ['Content-Type' => 'application/json'];
        if ($this->token) {
            $headers['Authorization'] = 'Bearer '.$this->token;
        }
        return $headers;
    }

    public function setToken(string|null $token): void
    {
        $this->token = $token;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @throws NomadMediaException
     */
    private function validateApiType(): void
    {
        if ($this->apiType !== 'portal') {
            throw new NomadMediaException("This function is only available for the 'portal' API type.");
        }
    }

    /**
     * @throws FatalRequestException
     * @throws NomadMediaException
     * @throws RequestException
     * @throws \JsonException
     */
    private function ensureInitialized(): void
    {
        if (is_null($this->token) && ! is_null($this->username) && ! is_null($this->password)) {
            $this->login();  // Automatically login if token is null
        }

        if (is_null($this->token)) {
            throw new NomadMediaException('Authentication required. Please provide a valid token.');
        }
    }

    /**
     * @param Request $request
     * @return Response
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function send(Request $request, MockClient $mockClient = null, callable $handleRetry = null): Response
    {
        if ($this->debugMode) {
            $this->logRequest($request);
        }

        $response = parent::send($request);

        if ($this->debugMode) {
            $this->logResponse($response);
        }

        return $response;
    }

    private function logRequest(Request $request): void
    {

        $this->consoleDebug('Requesting', $request->resolveEndpoint());

        $this->consoleDebug('Method', $request->getMethod()->value);

        $this->consoleDebug('Headers', $request->headers()->all());

        $this->consoleDebug('Body', $request->body()->all());
    }

    private function logResponse($response): void
    {

        $this->consoleDebug('Response Status', $response->status());

        $this->consoleDebug('Response Body', $response->body());
    }

    private function consoleDebug($title, $value): void
    {
        $colorStart = "\033[1;34m"; // ANSI code for bold blue text
        $colorEnd = "\033[0m";      // ANSI code to reset the color

        echo "\n";
        echo str_repeat('-', 80)."\n";
        echo "{$colorStart}DEBUG: $title{$colorEnd}\n"; // Color the title
        echo str_repeat('-', 80)."\n";

        if (is_array($value) || is_object($value)) {
            echo print_r($value, true); // Print array or object in readable format
        } else {
            echo $value."\n"; // Print string or other scalar values
        }

        echo str_repeat('-', 80)."\n";
    }

    /**
     * @return array
     * @throws NomadMediaException
     * @throws FatalRequestException
     * @throws RequestException
     * @throws \JsonException
     */
    public function login(): array
    {
        if (is_null($this->username) || is_null($this->password)) {
            throw new NomadMediaException('Username and password are required for login.');
        }

        $response = $this->send(new LoginRequest($this->username, $this->password));
        $this->setToken($response->json('token'));

        return $response->json();
    }

    /**
     * @throws FatalRequestException
     * @throws RequestException
     * @throws NomadMediaException
     * @throws \JsonException
     */
    public function logout(): void
    {
        $this->ensureInitialized();
        $this->send(new LogoutRequest($this->getToken()));
        $this->setToken(null);
    }

    /**
     * @return array
     * @throws NomadMediaException
     * @throws FatalRequestException
     * @throws RequestException
     * @throws \JsonException
     */
    public function refreshToken(): array
    {
        $this->ensureInitialized();
        $response = $this->send(new RefreshTokenRequest($this->getToken()));
        $this->setToken($response->json('token'));
        return $response->json();
    }

    // Content Manager Methods (portal-restricted)

    /**
     * @param string $userId
     * @param string $assetId
     * @throws FatalRequestException
     * @throws NomadMediaException
     * @throws RequestException
     * @throws \JsonException
     */
    public function clearContinueWatching(string $userId, string $assetId): void
    {
        $this->validateApiType();
        $this->ensureInitialized();

        $this->send(new ClearContinueWatchingRequest($userId, $assetId));
    }

    /**
     * @param string $userId
     * @throws NomadMediaException
     * @throws FatalRequestException
     * @throws RequestException
     * @throws \JsonException
     */
    public function clearWatchlist(string $userId): void
    {
        $this->validateApiType();
        $this->ensureInitialized();

        $this->send(new ClearWatchlistRequest($userId));
    }

    /**
     * @param string $formData
     * @return array
     * @throws NomadMediaException
     * @throws FatalRequestException
     * @throws RequestException
     * @throws \JsonException
     */
    public function createForm(string $formData): array
    {
        $this->validateApiType();
        $this->ensureInitialized();

        $response = $this->send(new CreateFormRequest((array) $formData));
        return $response->json();
    }

    /**
     * @param string $cookieId
     * @return array
     * @throws NomadMediaException
     * @throws FatalRequestException
     * @throws RequestException
     * @throws \JsonException
     */
    public function getContentCookies(string $cookieId): array
    {
        $this->validateApiType();
        $this->ensureInitialized();

        $response = $this->send(new GetContentCookiesRequest($cookieId));
        return $response->json();
    }

    /**
     * @throws FatalRequestException
     * @throws RequestException
     * @throws \JsonException
     */
    public function getDefaultSiteConfig(): array
    {
        $response = $this->send(new GetDefaultSiteConfigRequest());
        return $response->json();
    }

    /**
     * @param string $contentId
     * @return array
     * @throws NomadMediaException
     * @throws FatalRequestException
     * @throws RequestException
     * @throws \JsonException
     */
    public function getDynamicContents(string $contentId): array
    {
        $this->validateApiType();
        $this->ensureInitialized();

        $response = $this->send(new GetDynamicContentsRequest($contentId));
        return $response->json();
    }

    /**
     * @param string $groupId
     * @return array
     * @throws NomadMediaException
     * @throws FatalRequestException
     * @throws RequestException
     * @throws \JsonException
     */
    public function getMediaGroup(string $groupId): array
    {
        $this->validateApiType();
        $this->ensureInitialized();

        $response = $this->send(new GetMediaGroupRequest($groupId));
        return $response->json();
    }

    /**
     * @param string $mediaItemId
     * @return array
     * @throws FatalRequestException
     * @throws NomadMediaException
     * @throws RequestException
     * @throws \JsonException
     */
    public function getMediaItem(string $mediaItemId): array
    {
        $this->validateApiType();
        $this->ensureInitialized();

        $response = $this->send(new GetMediaItemRequest($mediaItemId));
        return $response->json();
    }

    /**
     * @param array $searchParams
     * @return array
     * @throws FatalRequestException
     * @throws NomadMediaException
     * @throws RequestException
     * @throws \JsonException
     */
    public function mediaSearch(array $searchParams): array
    {
        $this->validateApiType();
        $this->ensureInitialized();

        $response = $this->send(new MediaSearchRequest($searchParams));
        return $response->json();
    }

    /**
     * @param string $contentId
     * @return array
     * @throws FatalRequestException
     * @throws NomadMediaException
     * @throws RequestException
     * @throws \JsonException
     */
    public function getMyContent(string $contentId): array
    {
        $this->validateApiType();
        $this->ensureInitialized();

        $response = $this->send(new GetMyContentRequest($contentId));
        return $response->json();
    }

    /**
     * @param string $groupId
     * @return array
     * @throws FatalRequestException
     * @throws NomadMediaException
     * @throws RequestException
     * @throws \JsonException
     */
    public function getMyGroup(string $groupId): array
    {
        $this->validateApiType();
        $this->ensureInitialized();

        $response = $this->send(new GetMyGroupRequest($groupId));
        return $response->json();
    }

    /**
     * @param string $configId
     * @return array
     * @throws FatalRequestException
     * @throws NomadMediaException
     * @throws RequestException
     * @throws \JsonException
     */
    public function getSiteConfig(string $configId): array
    {
        $this->validateApiType();
        $this->ensureInitialized();

        $response = $this->send(new GetSiteConfigRequest($configId));
        return $response->json();
    }

    /**
     * @param string $username
     * @throws NomadMediaException
     * @throws FatalRequestException
     * @throws RequestException
     * @throws \JsonException
     */
    public function forgotPassword(string $username): void
    {
        $this->ensureInitialized();
        $this->send(new ForgotPasswordRequest($username));
    }

    /**
     * @param string $username
     * @param string $token
     * @param string $newPassword
     * @throws NomadMediaException
     * @throws FatalRequestException
     * @throws RequestException
     * @throws \JsonException
     */
    public function resetPassword(string $username, string $token, string $newPassword): void
    {
        $this->ensureInitialized();
        $this->send(new ResetPasswordRequest($username, $token, $newPassword));
    }
}
