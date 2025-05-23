# NomadMedia SDK - PHP

The **NomadMedia SDK** for PHP provides a simple, intuitive interface for interacting with the NomadMedia API. It supports authentication, content management, and utility methods to enable easy integration with the NomadMedia platform.

## Table of Contents

- [Installation](#installation)
- [Requirements](#requirements)
- [Configuration](#configuration)
- [Usage](#usage)
    - [Authentication](#authentication)
    - [Content Management](#content-management)
    - [Utility Methods](#utility-methods)
- [Testing](#testing)
- [Contribution](#contribution)
- [License](#license)

## Installation

To install the SDK, use Composer:

```bash
composer require prisonfellowship/nomad-php-sdk
```


---


## Requirements

- PHP 8.1 or higher
- Composer
- [Saloon v3.10.0](https://docs.saloon.dev/getting-started/introduction) (for HTTP requests)

### Development Requirements

The following packages are required for development:

- [Pest v2.35](https://pestphp.com/) (for testing)
- [PHPStan v1.11](https://phpstan.org/) (for static analysis)
- [Laravel Pint v1.17](https://github.com/laravel/pint) (for code linting)

## Configuration

To initialize the SDK, create an instance of the `NomadMediaConnector` with the necessary configuration options:

```php
use PrisonFellowship\NomadPHPSDK\NomadMediaConnector;

$connector = new NomadMediaConnector([
    'serviceApiUrl' => 'https://api.example.com',  // Your API base URL
    'username' => 'your_username',                // For authentication
    'password' => 'your_password',                // For authentication
    'apiType' => 'portal',                        // Can be 'portal' or 'admin'
    'debugMode' => true,                          // Optional: Debug mode for logging API calls
]);
```


---

## Usage

### Authentication

#### **Login**

```php
$response = $connector->login();
$token = $connector->getToken();  // Save the token
```

#### **Logout**

```php
$connector->logout();
```

#### **Refresh Token**

```php
$response = $connector->refreshToken();
$token = $connector->getToken();  // Save the new token
```

### Content Management

#### **Get Default Site Configuration**

```php
$response = $connector->getDefaultSiteConfig();
print_r($response);
```

#### **Get Media Group**

```php
$response = $connector->getMediaGroup('0df0f00b-0b00-00c0-000e-0c0fca000ae');
print_r($response);
```

### Utility Methods

#### **Forgot Password**

```php
$connector->forgotPassword('your_username');
```

#### **Forgot Password**

```php
$connector->forgotPassword('your_username');
```

#### **Reset Password**

```php
$username = 'your_username';
$token = 'reset_token';
$newPassword = 'new_secure_password';
$connector->resetPassword($username, $token, $newPassword);
```
---

### Debug Mode

If `debugMode` is set to `true` during initialization, the SDK will send detailed API request and response information to **LaraDumps**, allowing you to debug visually in real time with a clean and organized interface.

```php
$connector = new NomadMediaConnector([
    'serviceApiUrl' => 'https://api.example.com',
    'debugMode' => true,  // Enable debug mode with LaraDumps integration
]);
```

When debug mode is enabled, each request and response will be sent to the **LaraDumps app** in a dedicated screen. For example:

- ✅ `Request` screen will show:
  - Endpoint
  - HTTP Method
  - Headers
  - Body
  - Query parameters

- ✅ `Response` screen will show:
  - Status code
  - Response body

---

### LaraDumps Setup

To use this feature, you must install the **LaraDumps Desktop App**:

1. Download it from the official repository:  
   👉 [https://github.com/laradumps/app/releases](https://github.com/laradumps/app/releases)

2. Install and run the app. It should run in the background and automatically receive logs.

3. (Optional) You can open dedicated **screens** inside the app to organize logs, such as “Request”, “Response”, etc.

---

## Testing

### Running Tests

This SDK uses **Pest** for testing. To run the tests:

1. Install the development dependencies:

```bash
composer install --dev
```
2. Run the tests:

```bash
composer test
```
### Linting
3. You can run Laravel Pint to check for coding standards violations:

```bash
composer run lint
```

### Static Analysis
4. You can run PHPStan to perform static analysis on the codebase:

```bash
composer run analyse
```

---

## Contribution

Contributions are welcome! Please follow the steps below to contribute:

1. Fork the repository.
2. Create a new branch with a descriptive name (e.g., `feature-new-method`).
3. Make your changes.
4. Write tests for any new functionality or changes.
5. Submit a pull request with a detailed description of your changes.

### Coding Standards

Please ensure your code adheres to PSR-12 coding standards.

### Running Lint

You can run the linter to check for coding standards violations:

```bash
composer run lint
```


---

## License

This SDK is open-source and available under the [MIT License](LICENSE).

