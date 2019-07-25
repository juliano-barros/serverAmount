<?php

namespace ServerAmount\Machine;

abstract Class ServerManagerAbstract{

	private int $numberOfServers;

	protected MachineInterface $baseMachine

    abstract protected function isNeededNewServer();

    abstract protected function addResource();

    abstract protected function decreaseResource(MachineInterface $machine);

    abstract protected function getOverMachine();

    abstract protected function resetOverMachine()

    public function setBaseMachine(MachineInterface $baseMachine){
    	$this->baseMachine  $baseMachine
    }

    private function addServer(){
    	$this->numberOfServers++;
    }

	public function addVirtualMachine(MachineInterface $machine){
		
		$this->decreaseResource($machine);

		if (!$this->isNeededNewServer()){

			$this->addServer();

			$machine = $this->getOverMachine();
			
			$this->resetOverMachine();
			$this->addResource();
			$this->addVirtualMachine($machine);

		}
		
	}

	public function getNumberOfServers(){
		return $this->numberOfServers;
	}

}