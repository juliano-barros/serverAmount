<?php

namespace ServerAmount\Exceptions;

/**
 * This class is responsible for throw an error when no virtual machine is passed
 */
class InvalidVirtualMachineException extends \Exception {

	const ERROR_INVALID_VIRTUAL_MACHINE = 1;

    public function __construct( string $message = 'Invalid virtual machine' ){
        parent::__construct($message, self::ERROR_INVALID_VIRTUAL_MACHINE);
    }

}