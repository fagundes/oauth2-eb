# EB/DGP Provider for OAuth 2.0 Client

This package provides EB/DGP OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation

```
composer require fagundes/oauth2-eb
```

## Usage

```php
$ebProvider = new \MichaelKaefer\OAuth2\Client\Provider\EB([
    'clientId'                => 'yourId',          // The client ID assigned to you by EB
    'clientSecret'            => 'yourSecret',      // The client password assigned to you by EB
    'redirectUri'             => 'yourRedirectUri'  // The return URL you specified for your app on EB
]);

// Get authorization code
if (!isset($_GET['code'])) {
    // Get authorization URL
    $authorizationUrl = $ebProvider->getAuthorizationUrl();

    // Get state and store it to the session
    $_SESSION['oauth2state'] = $ebProvider->getState();

    // Redirect user to authorization URL
    header('Location: ' . $authorizationUrl);
    exit;
// Check for errors
} elseif (empty($_GET['state']) || (isset($_SESSION['oauth2state']) && $_GET['state'] !== $_SESSION['oauth2state'])) {
    if (isset($_SESSION['oauth2state'])) {
        unset($_SESSION['oauth2state']);
    }
    exit('Invalid state');
} else {
    // Get access token
    try {
        $accessTokenAndResourceOwner = $ebProvider->getAccessToken(
            'authorization_code',
            [
                'code' => $_GET['code']
            ]
        );
    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
        exit($e->getMessage());
    }

    // NOT AVAILABLE YET!
    // // Get resource owner
    // try {
    //     $resourceOwner = $ebProvider->getResourceOwner($accessToken);
    // } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
    //     exit($e->getMessage());
    // }
        
    // Now you can store the results to session etc.
    $_SESSION['accessToken'] = $accessTokenAndResourceOwner->getToken();
    $_SESSION['resourceOwner'] = $accessTokenAndResourceOwner->getId();
    
    var_dump(
        $accessTokenAndResourceOwner->getIdentidade(),
        $accessTokenAndResourceOwner->getNomeGuerra(),
        $accessTokenAndResourceOwner->getOrgaoSigla(),
        $accessTokenAndResourceOwner->toArray()
    );
}
```

For more information see the PHP League's general usage examples.

## Testing

``` bash
$ ./vendor/bin/phpunit
```

## License

The MIT License (MIT). Please see [License File](https://github.com/fagundes/oauth2-eb/blob/master/LICENSE) for more information.
