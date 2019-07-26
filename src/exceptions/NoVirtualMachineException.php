<?php

namespace ServerAmount\Exceptions;

/**
 * This class is responsible for throw an error when no virtual machine is passed
 */
class NoVirtualMachineException extends \Exception {

    
	const ERROR_NO_VIRTUAL_MACHINE = 1;

    public function __construct( string $message = 'No virtual machine' ){
        parent::__construct($message, self::ERROR_NO_VIRTUAL_MACHINE);
    }

}