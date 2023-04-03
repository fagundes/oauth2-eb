<?php

namespace Fagundes\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Tool\ArrayAccessorTrait;
use League\OAuth2\Client\Token\AccessToken;

class EBResourceOwner extends AccessToken implements ResourceOwnerInterface
{
    use ArrayAccessorTrait;

    /**
     * Get resource owner id
     *
     * @return string|null
     */
    public function getId()
    {
        return $this->getIdentidade();
    }

    /**
     * Get resource owner identidade
     *
     * @return string|null
     */
    public function getIdentidade()
    {
        return $this->getValueByKey($this->values, 'INF_MIL_BASICO.MILITAR_IDENTIDADE');
    }

    /**
     * Get resource owner posto/graduacao sigla
     *
     * @return string|null
     */
    public function getPostoGraduacaoSigla()
    {
        return $this->getValueByKey($this->values, 'INF_MIL_BASICO.POSTO_GRADUACAO_SIGLA');
    }

    /**
     * Get resource owner nome de guerra
     *
     * @return string|null
     */
    public function getNomeGuerra()
    {
        return $this->getValueByKey($this->values, 'INF_MIL_BASICO.NOME_GUERRA');
    }

    /**
     * Get resource owner nome completo
     *
     * @return string|null
     */
    public function getNomeCompleto()
    {
        return $this->getValueByKey($this->values, 'INF_MIL_BASICO.NOME_MILITAR');
    }

    /**
     * Get resource owner orgao codom
     *
     * @return string|null
     */
    public function getOrgaoCodom()
    {
        return $this->getValueByKey($this->values, 'INF_MIL_BASICO.OM_CODOM');
    }

    /**
     * Get resource owner orgao sigla
     *
     * @return string|null
     */
    public function getOrgaoSigla()
    {
        return $this->getValueByKey($this->values, 'INF_MIL_BASICO.OM_SIGLA');
    }

    /**
     * Get resource owner orgao nome
     *
     * @return string|null
     */
    public function getOrgaoNome()
    {
        return $this->getValueByKey($this->values, 'INF_MIL_BASICO.OM_NOME');
    }

    /**
     * Return all of the owner details available as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->jsonSerialize();
    }
}