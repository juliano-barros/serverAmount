<?php

namespace ServerAmount\Machine;

/**
 * This class is used as base for any kind of server, each server should implement their own 
 * properties/resources
 */ 
Class ServerManager{

	use BasicMachineTrait;

    /** @var int $numberOfServers */
	private $numberOfServers;

    /** @var ServerMachine $baseMachine */
	protected $baseMachine;

    // If any cpu, hdd or ram is running below 0 we need a new server
    protected function isNeededNewServer(VirtualMachine $machine) : bool {
    	return ( 
                  ($this->getCpu() < $machine->getCpu()) || 
                  ($this->getHdd() < $machine->getHdd()) || 
                  ($this->getRam() < $machine->getRam()) 
              ); 
    }

    // Decreses the resource based on machine 
    protected function decreaseResource(VirtualMachine $machine): void {
    	$this->setCpu($this->getCpu() - $machine->getCpu());
    	$this->setRam($this->getRam() - $machine->getRam());
        $this->setHdd($this->getHdd() - $machine->getHdd());
    }


    // Sets the base machine
    public function setBaseMachine(ServerMachine $baseMachine) : void{

        $this->setResource( $baseMachine->getCpu(), $baseMachine->getRam(), $baseMachine->getHdd());

    	$this->baseMachine = $baseMachine;
    }   

    // Increases the number of servers
    private function addServer(){
    	$this->numberOfServers++;
        $this->setBaseMachine($this->baseMachine);
    }

    // Adds a new virtual machine to the current server
	public function addVirtualMachine(VirtualMachine $machine) : void{
		
		if( $this->isNeededNewServer($machine)){
            $this->addServer();
        }

		$this->decreaseResource($machine);		
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