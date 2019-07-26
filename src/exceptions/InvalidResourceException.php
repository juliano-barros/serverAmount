<?php

namespace ServerAmount\Exceptions;

/**
 * This class is responsible for throw an error when no virtual machine is passed
 */
class InvalidResourceException extends \Exception {

	const ERROR_INVALID_RESOURCE = 1;

    public function __construct( string $message = 'Invalid resource' ){
        parent::__construct($message, self::ERROR_INVALID_RESOURCE);
    }

}