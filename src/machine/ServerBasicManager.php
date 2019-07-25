<?php

namespace ServerAmount\Machine;

class ServerBasicManager extends ServerManagerAbstract{

	use BasicMachineTrait;

    protected function isNeededNewServer(){
    	return ( ( $this->getCpu() < 0 ) || ($this->getHdd() < 0 ) || ($this->getRam() < 0) ); 
    }

    protected function addResource(){
    	$this->setCpu( $this->gettCpu() + $this->baseMachine->getCpu());
    	$this->setHdd( $this->gettHdd() + $this->baseMachine->getHdd());
    	$this->setRam( $this->gettRam() + $this->baseMachine->getRam());
    }

    protected function decreaseResource(VirtualBasicMachine $machine){
    	$this->setCpu( -$machine->getCpu());
    	$this->setHdd( -$machine->getHdd());
    	$this->setRam( -$machine->getRam());
    }

    protected function getOverMachine(){
    	
    	$overMachine = new ServerBasicMachine();
    	$overMachhine->setCpu( $this->getCpu() < 0 ? $this->getCpu() : 0);
    	$overMachhine->setHdd( $this->getHdd() < 0 ? $this->getHdd() : 0);
    	$overMachhine->setRam( $this->getRam() < 0 ? $this->getRam() : 0);

    	return $overMachine;

    }

    protected function resetOverMachine(){
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