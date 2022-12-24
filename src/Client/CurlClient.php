<?php
/*
 * @link http://eximiaweb.com.br/
 * @copyright Copyright (c) 2015-2022 ExímiaWeb Informática
 * @license 
 * Desenvolvido por: EximiaWeb Informática
 */
namespace BrasilAPI\Client;

/**
 * Cliente básico usando curl
 *
 * @author Rodrigo Teixeira Andreotti <ro.andriotti@gmail.com>
 */
class CurlClient implements Client
{

    /**
     * Default headers
     * @var array
     */
    private $headers = [
        'Content-Type: application/json',
        'Accept: application/json'
    ];

    /**
     * Handler curl
     * @var resource
     */
    private $handler = null;

    /**
     * 
     * @var \BrasilAPI\Services\System\LogInterface
     */
    private $logger;

    /**
     * @var \BrasilAPI\Services\System\CacheInterface
     */
    private $cacheDriver = null;
    
    private $defaultOptions = [
        CURLOPT_USERAGENT => 'BrasilAPI PHP SDK',
        CURLOPT_RETURNTRANSFER => true
    ];

    public function __construct(
        $useSecureSsl = true,
        \BrasilAPI\Services\System\LogInterface $logger = null,
        \BrasilAPI\Services\System\CacheInterface $cacheDriver = null
    )
    {
        $this->logger = $logger;
        $this->cacheDriver = $cacheDriver;
        
        if(!$useSecureSsl) {
            $this->defaultOptions[CURLOPT_SSL_VERIFYPEER] = false;
            $this->defaultOptions[CURLOPT_SSL_VERIFYHOST] = false;
        }
    }

    private function getHandler()
    {
        if (!$this->handler) {
            $this->handler = curl_init();
            curl_setopt_array($this->handler, $this->defaultOptions);
        }

        return $this->handler;
    }

    /**
     * Faz um request ao endpoint especificado com o método indicado
     * @param string $method
     * @param string $url
     * @param array $customHeaders
     * @return object
     */
    public function request(string $url, array $queryString = [], array $customHeaders = []): object
    {
        $finalUrl = $url . ($queryString ? '?' . http_build_query($queryString) : '');

        if ($this->cacheDriver) {
            $response = $this->cacheDriver->get($finalUrl);
            if ($response) {
                return json_decode($response);
            }
        }

        if ($customHeaders) {
            $headers = array_merge($this->headers, $customHeaders);
        }


        $ch = $this->getHandler();

        curl_setopt_array($ch, [
            CURLOPT_URL => $finalUrl,
            CURLOPT_HEADER => $headers,
        ]);

        $response = curl_exec($ch);

        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($this->logger) {
            $time = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
            $this->logger->log($url, $httpStatusCode, $response, $time);
        }

        if ($httpStatusCode >= 400 && $httpStatusCode < 500) {
            throw new ClientException($response, $httpStatusCode);
        } elseif ($httpStatusCode >= 500) {
            throw new ServerException($response, $httpStatusCode);
        }

        if ($this->cacheDriver) {
            $this->cacheDriver->set($finalUrl, $response);
        }

        return json_decode($response);
    }
}
