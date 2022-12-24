<?php
/*
 * @link http://eximiaweb.com.br/
 * @copyright Copyright (c) 2015-2022 ExímiaWeb Informática
 * @license 
 * Desenvolvido por: EximiaWeb Informática
 */
namespace BrasilAPI\Services\System;

/**
 * Interface para cache do sdk
 * @author Rodrigo Teixeira Andreotti <ro.andriotti@gmail.com>
 */
interface CacheInterface
{
    /**
     * Salva um item no cache
     * @param string $key
     * @param string $value
     */
    public function set(string $key, string $value);
    
    /**
     * Obtém um item do cache (a implementação cache deve ser responsável por 
     * invalidar o valor após o tempo desejado)
     * @param string $key
     * @return string
     */
    public function get(string $key): string;
}
