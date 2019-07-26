<?php

use PHPUnit\Framework\TestCase;
use ServerAmount\Check\ServerAmount;
use ServerAmount\Check\NoVirtualMachineException;
use ServerAmount\Machine\ServerBasicManager;
use ServerAmount\Machine\ServerBasicMachine;
use ServerAmount\Machine\VirtualBasicMachine;

/**
 * Class to test ServerAmount class
 */ 
final class ServerAmountTest extends TestCase{

    // Tests differents kind of configuration.
    // Validates if the number of servers is returning correctly
    public function testVirtualMachine(): void
    {

    	$serverBasicManager = new ServerBasicManager();
    	$serverAmount = new ServerAmount($serverBasicManager);

    	$serverBasicMachine = new ServerBasicMachine();
    	$serverBasicMachine->setResource(2, 32, 100);
    	
        // Adds virtual machines
    	$virtualMachines[] = $this->createVirtualMachine(1, 32, 10);
    	$virtualMachines[] = $this->createVirtualMachine(2, 16, 10);
    	$virtualMachines[] = $this->createVirtualMachine(2, 16, 100);

    	$serverNeeded = $serverAmount->calculate( $serverBasicMachine, $virtualMachines);

    	// 3 servers since we have 5 processors
    	$this->assertEquals(3, $serverNeeded);

        // Adds virtual machines
    	$virtualMachines[] = $this->createVirtualMachine(3, 32, 100);
    	$virtualMachines[] = $this->createVirtualMachine(4, 32, 10);
        $virtualMachines[] = $this->createVirtualMachine(4, 16, 100);
        $virtualMachines[] = $this->createVirtualMachine(4, 16, 100);

    	$serverNeeded = $serverAmount->calculate( $serverBasicMachine, $virtualMachines);

    	// 10 servers since in total we have 20 processors
    	$this->assertEquals(10, $serverNeeded);

        // Sets a new base machine
    	$serverBasicMachine->setResource(8, 32, 1000);

        // Adds new virtual machines
    	$virtualMachines = [];
    	$virtualMachines[] = $this->createVirtualMachine(3, 32, 10000);
    	$virtualMachines[] = $this->createVirtualMachine(3, 32, 1000);
    	$virtualMachines[] = $this->createVirtualMachine(3, 32, 1000);

    	$serverNeeded = $serverAmount->calculate( $serverBasicMachine, $virtualMachines);

    	// 12 servers since we have 12000 hdd to allocate
    	$this->assertEquals(12, $serverNeeded);

    }

    // Method to create virtual machines
    private function createVirtualMachine(int $cpu, int $ram, int $hdd): VirtualBasicMachine {
    	
    	$virtualMachine = new VirtualBasicMachine();
    	$virtualMachine->setResource($cpu, $ram, $hdd);

    	return $virtualMachine;
    }

    // Tests if NoVirtualMachineException is thrown when we don't pass the virtual machines
    public function testNoVirtualMachine(): void {

    	$serverBasicManager = new ServerBasicManager();
    	$serverAmount = new ServerAmount($serverBasicManager);

    	$serverBasicMachine = new ServerBasicMachine();
    	$serverBasicMachine->setResource(2, 32, 100);
    	
    	$virtualMachines = [];
    	try{
    		$serverNeeded = $serverAmount->calculate( $serverBasicMachine, $virtualMachines);
    	}catch(NoVirtualMachineException $e){
    		$this->assertEquals(NoVirtualMachineException::ERROR_NO_VIRTUAL_MACHINE, $e->getCode());
    	}

    }

}