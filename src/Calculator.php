<?php

namespace ServerAmount;

use ServerAmount\Server\ServerManager;
use ServerAmount\Machine\ServerMachine;
use ServerAmount\Machine\VirtualMachine;
use ServerAmount\Exceptions\NoVirtualMachineException;

/**
 * Class responsible for calculate how many servers are needed
 */
class Calculator{


    /**
     * This method receives the base server and all virtual machines we need.
     * The server manager calculates the number of servers needed
     *
     * @param ServerMachine $serverType
     * @param Virtualmachine[] $vritualMachines
     * @return int 
     */
	public function calculate( ServerMachine $serverType, array $virtualMachines) : int{
		
		$serverManager = new ServerManager($serverType);

		// If there is no virtual machine, an exception is thrown.
		if ( count($virtualMachines) <= 0 ){
			throw new NoVirtualMachineException();
		}

		// Sets 1 if we need to call calculate method more the once
		$serverManager->setNumberOfServers(1);

		// This is the base machine which will be got as default.
		$serverManager->setBaseMachine($serverType);

		/**
		 * Adding all virtual machines to manager, so it can calculate the number of servers
		 *
		 * @var VirtualMachine $virtualMachine
		 */
		foreach( $virtualMachines as $virtualMachine){
			$serverManager->addVirtualMachine($virtualMachine);
		}

		return $serverManager->getNumberOfServers();

	}
}