<?php

namespace ServerAmount\Check;

use ServerAmount\Machine\ServerManager;
use ServerAmount\Machine\ServerMachine;
use ServerAmount\Machine\VirtualMachine;

/**
 * Class responsible for calculate how many servers are needed
 */
class Calculator{


	/**
	 * @var ServerManager $serverManager
	 */
	private $serverManager;

    // Its constructor should receive the server manager, abstract because we might
    // need different kind of managers
	public function __construct(ServerManager $serverManager){
		$this->serverManager = $serverManager;
	}

    /**
     * This method receives the base server and all virtual machines we need.
     * The server manager calculates the number of servers needed
     *
     * @param ServerMachine $serverType
     * @param Virtualmachine[] $vritualMachines
     * @return int 
     */
	public function calculate( ServerMachine $serverType, array $virtualMachines) : int{
		
		// If there is no virtual machine, an exception is thrown.
		if ( count($virtualMachines) <= 0 ){
			throw new NoVirtualMachineException();
		}

		// Sets 1 if we need to call calculate method more the once
		$this->serverManager->setNumberOfServers(1);

		// This is the base machine which will be got as default.
		$this->serverManager->setBaseMachine($serverType);

		/**
		 * Adding all virtual machines to manager, so it can calculate the number of servers
		 *
		 * @var VirtualMachine $virtualMachine
		 */
		foreach( $virtualMachines as $virtualMachine){
			$this->serverManager->addVirtualMachine($virtualMachine);
		}

		return $this->serverManager->getNumberOfServers();

	}
}