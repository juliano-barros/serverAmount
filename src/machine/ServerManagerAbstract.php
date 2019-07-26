<?php

namespace ServerAmount\Machine;

abstract Class ServerManagerAbstract{

	private $numberOfServers;

	protected $baseMachine;

	// Method needed to check if we need a new server
    abstract protected function isNeededNewServer() : bool;

    // Adds a base resource based on base machine
    abstract protected function addBaseResource() : void;

    // Decreases the current resource from a virtual machine that has been added
    abstract protected function decreaseResource(MachineInterface $machine) : void;

    // Method which will return the machine base on negative properties
    abstract protected function getOverMachine() : MachineInterface;

    // Sets initial value to a machine, so we could add a new virtual machine again
    abstract protected function resetOverMachine() : void;

    // Sets the base machine
    public function setBaseMachine(MachineInterface $baseMachine) : void{
    	$this->baseMachine = $baseMachine;
    }

    // Increases the number of servers
    private function addServer(){
    	$this->numberOfServers++;
    }

    // Adds a new virtual machine to the current server
	public function addVirtualMachine(MachineInterface $machine) : void{
		
		$this->decreaseResource($machine);

		if ($this->isNeededNewServer()){

			$this->addServer();

			// Gets a over machine to recursively call this method
			$machine = $this->getOverMachine();

			$this->resetOverMachine();
			$this->addBaseResource();
			$this->addVirtualMachine($machine);

		}
		
	}

	// Returns the number of servers
	public function getNumberOfServers() : int{
		return $this->numberOfServers;
	}

	// Sets the number of servers
	public function setNumberOfServers(int $numberOfServers) : void{
		$this->numberOfServers = $numberOfServers;
	}

}