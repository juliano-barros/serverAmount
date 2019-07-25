<?php

namespace ServerAmount\Check;

use ServerAmount\Machine\ServerManagerAbstract;
use ServerAmount\Machine\MachineInterface;

class ServerAmount{

	const public ERROR_NO_VIRTUAL_MACHINE = 1;

	private ServerManagerAbstract $serverManager;

	public function __construct(ServerManagerAbstract $serverManager){
		$this->serverManager = $serverManager;
	}

	public function calculate( MachineInterface $serverType, array $virtualMachines):int{
		
		if ( count($virtualMachines) <= 0 ){
			throw new Exception('Virtual machines are mandatore to calculate servers', self::ERROR_NO_VIRTUAL_MACHINE);
		}

		$this->serverManager->setBaseMachine($serverType);

		forach( $virtualMachines as $virtualMachine){
			$this->serverManager->addVrittualMachine($virtualmachine);
		}

		return $tis->serverManager->getNumberOfServers();
	}
}