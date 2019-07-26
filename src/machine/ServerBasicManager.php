<?php

namespace ServerAmount\Machine;

/**
 * This class is responsible to manage basic servers (hdd, ram, cpu)
 */
class ServerBasicManager extends ServerManagerAbstract{

	use BasicMachineTrait;

    // If any cpu, hdd or ram is running below 0 we need a new server
    protected function isNeededNewServer() : bool {
    	return ( ( $this->getCpu() < 0 ) || ($this->getHdd() < 0 ) || ($this->getRam() < 0) ); 
    }

    // Adds the base server machine to the current machine
    protected function addBaseResource(): void{
    	$this->setCpu( $this->getCpu() + $this->baseMachine->getCpu());
    	$this->setRam( $this->getRam() + $this->baseMachine->getRam());
        $this->setHdd( $this->getHdd() + $this->baseMachine->getHdd());
    }

    // Sets the base machine
    public function setBaseMachine(MachineInterface $baseMachine) : void{
        
        parent::setBaseMachine($baseMachine);

        $this->setResource( $baseMachine->getCpu(), $baseMachine->getRam(), $baseMachine->getHdd());

    }    

    // Decreses the resource based on machine 
    protected function decreaseResource(MachineInterface $machine): void {
    	$this->setCpu($this->getCpu() - $machine->getCpu());
    	$this->setRam($this->getRam() - $machine->getRam());
        $this->setHdd($this->getHdd() - $machine->getHdd());
    }

    // Overmachine is the negative machine taken after a virtual machine is added
    protected function getOverMachine() : MachineInterface{
    	
        // Here we could have ServerBasicMahcine or VirtualBasicMachine
    	$overMachine = new VirtualBasicMachine();

        // Gets from abs of properties because shouldn't exist a negative machine
    	$overMachine->setCpu( $this->getCpu() < 0 ? abs($this->getCpu()) : 0);
    	$overMachine->setRam( $this->getRam() < 0 ? abs($this->getRam()) : 0);
        $overMachine->setHdd( $this->getHdd() < 0 ? abs($this->getHdd()) : 0);

    	return $overMachine;

    }

    // Sets 0 to the negative properties to add the base machine again
    // The manager abstract calls it after it gets the overmachine 
    protected function resetOverMachine() : void {

    	if($this->getCpu() < 0){
    		$this->setCpu(0);
    	}

    	if($this->getHdd() < 0){
    		$this->setHdd(0);
    	}

    	if($this->getRam() < 0){
    		$this->setRam(0);
    	}

    }

}