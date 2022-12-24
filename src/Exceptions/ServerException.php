<?php
/*
 * @link http://eximiaweb.com.br/
 * @copyright Copyright (c) 2015-2022 ExímiaWeb Informática
 * @license 
 * Desenvolvido por: EximiaWeb Informática
 */
namespace BrasilAPI\Exceptions;

/**
 * Excessões para erros de client (4xx)
 *
 * @author Rodrigo Teixeira Andreotti <ro.andriotti@gmail.com>
 */
class ClientException extends \RuntimeException
{
    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null): \Exception
    {
        return parent::__construct($message, $code, $previous);
    }
}
