<?php

namespace ServerAmount\Server;

use ServerAmount\Machine\BasicMachineTrait;
use ServerAmount\Machine\VirtualMachine;
use ServerAmount\Machine\ServerMachine;
use ServerAmount\Machine\BasicMachineAbstract;
use ServerAmount\Exceptions\InvalidResourceException;
use ServerAmount\Exceptions\InvalidVirtualMachineException;

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

    public function __construct(ServerMachine $serverBase){
        $this->setBaseMachine($serverBase);
    }

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

    // Checks if the machine has an empty resource
    private function hasEmptyResource(BasicMachineAbstract $machine) : bool{
        
        return ( ($machine->getCpu() <= 0) ||
                 ($machine->getHdd() <= 0) ||
                 ($machine->getRam() <= 0) );

    }

    // Sets the base machine
    public function setBaseMachine(ServerMachine $baseMachine) : void{

        if ( $this->hasEmptyResource($baseMachine) ){
            throw new InvalidResourceException();
        }

        $this->setResource( $baseMachine->getCpu(), $baseMachine->getRam(), $baseMachine->getHdd());

    	$this->baseMachine = $baseMachine;
    }   

    // Increases the number of servers
    private function addServer(){
    	$this->numberOfServers++;
        $this->setBaseMachine($this->baseMachine);
    }

    // Virtual machines can't be bigger than the base server nor has any property equal 0
    private function isInvalidVirtualMachine(VirtualMachine $machine) : bool{
        return ( 
            ( $machine->getCpu() > $this->baseMachine->getCpu()) ||
            ( $machine->getHdd() > $this->baseMachine->getHdd()) ||
            ( $machine->getRam() > $this->baseMachine->getRam()) ||
            ( $this->hasEmptyResource($machine) ));
    }

    // Adds a new virtual machine to the current server
	public function addVirtualMachine(VirtualMachine $machine) : void{
		
        if ( $this->isInvalidVirtualMachine($machine)){
            throw new InvalidVirtualMachineException();
        }

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