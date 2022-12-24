<?php
/*
 * @link http://eximiaweb.com.br/
 * @copyright Copyright (c) 2015-2022 ExímiaWeb Informática
 * @license 
 * Desenvolvido por: EximiaWeb Informática
 */
namespace BrasilAPI\Client;

/**
 * Interface para clientes customizados
 * @author Rodrigo Teixeira Andreotti <ro.andriotti@gmail.com>
 */
interface Client
{
    /**
     * Faz um request ao endpoint especificado com o método indicado
     * @param string $method
     * @param string $url
     * @param array $customHeaders
     * @return object
     */
    public function request(string $url, array $queryString = [], array $customHeaders = []): object;
}
