<?php
/*
 * @link http://eximiaweb.com.br/
 * @copyright Copyright (c) 2015-2022 ExímiaWeb Informática
 * @license 
 * Desenvolvido por: EximiaWeb Informática
 */
namespace BrasilAPI\Services\System;

/**
 * Interface para logs do SDK
 * @author Rodrigo Teixeira Andreotti <ro.andriotti@gmail.com>
 */
interface LogInterface
{
    /**
     * Grava um registro no log
     * @param string $url
     * @param int $httpStatusCode
     * @param string $response
     * @param int $time
     */
    public function log(string $url, int $httpStatusCode, string $response, int $time);
}
