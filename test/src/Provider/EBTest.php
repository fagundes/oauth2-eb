<?php

namespace Fagundes\OAuth2\Client\Test\Provider; 

use Fagundes\OAuth2\Client\Provider\EB;
use Mockery;
use PHPUnit\Framework\TestCase;

class EBTest extends TestCase
{
    protected $provider;

    protected function setUp(): void
    {
        $this->provider = new EB([
            'clientId' => 'mock_client_id',
            'clientSecret' => 'mock_secret',
            'redirectUri' => 'mock_redirect_uri',
        ]);
    }

    public function testAuthorizationUrl()
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);
        parse_str($uri['query'], $query);

        $this->assertArrayHasKey('client_id', $query);
        $this->assertArrayHasKey('response_type', $query);
        $this->assertArrayHasKey('redirect_uri', $query);
    }

    public function testGetBaseAccessTokenUrl()
    {
        $params = [];

        $url = $this->provider->getBaseAccessTokenUrl($params);
        $uri = parse_url($url);

        $this->assertEquals('/token', $uri['path']);
    }

    public function testGetAuthorizationUrl()
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);

        $this->assertEquals('/authorize', $uri['path']);
    }

    public function testGetAccessToken()
    {
        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getHeader')
        ->times(1)
            ->andReturn('application/json');
        $response->shouldReceive('getBody')
        ->times(1)
            ->andReturn(
                <<<JSON
                    {
                        "access_token":"mock_access_token",
                        "expires_in":3600,
                        "last_acess":"mock_last_access_date",
                        "INF_MIL_BASICO": {
                            "MILITAR_IDENTIDADE": "0123456789",
                            "POSTO_GRADUACAO_SIGLA": "Cap",
                            "NOME_GUERRA": "TAL",
                            "NOME_MILITAR": "FULANO DE TAL",
                            "OM_CODOM": "012345",
                            "OM_SIGLA": "DZEx",
                            "OM_NOME": "Diretoria Z do Exército"
                        }
                    }
                JSON
            );

        $client = Mockery::mock('GuzzleHttp\ClientInterface');
        $client->shouldReceive('send')->times(1)->andReturn($response);
        $this->provider->setHttpClient($client);

        $tokenAndOwner = $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);

        // token data
        $this->assertEquals('mock_access_token', $tokenAndOwner->getToken());
        $this->assertLessThanOrEqual(time() + 3600, $tokenAndOwner->getExpires());
        $this->assertGreaterThanOrEqual(time(), $tokenAndOwner->getExpires());
        $this->assertNull($tokenAndOwner->getRefreshToken(), 'EB does not return refresh token with access token. Excepted null.');
        $this->assertNull($tokenAndOwner->getResourceOwnerId(), 'Amazon does not return user ID with access token. Expected null.');

        // owner data
        $this->assertEquals('0123456789', $tokenAndOwner->getId());
        $this->assertEquals('0123456789', $tokenAndOwner->getIdentidade());
        $this->assertEquals('Cap', $tokenAndOwner->getPostoGraduacaoSigla());
        $this->assertEquals('TAL', $tokenAndOwner->getNomeGuerra());
        $this->assertEquals('FULANO DE TAL', $tokenAndOwner->getNomeCompleto());
        $this->assertEquals('012345', $tokenAndOwner->getOrgaoCodom());
        $this->assertEquals('DZEx', $tokenAndOwner->getOrgaoSigla());
        $this->assertEquals('Diretoria Z do Exército', $tokenAndOwner->getOrgaoNome());
    }
}