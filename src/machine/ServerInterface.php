<?php

namespace ServerAmount\Machine;


interface ServerManagerInterface implements MachineInterface{

	public function addVirtualMachine(MachineInterface $machine);

	public function getNumberOfSerers();
	
}