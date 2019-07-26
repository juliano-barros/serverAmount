<?php

use PHPUnit\Framework\TestCase;
use ServerAmount\Check\Calculator;
use ServerAmount\Check\NoVirtualMachineException;
use ServerAmount\Machine\ServerManager;
use ServerAmount\Machine\ServerMachine;
use ServerAmount\Machine\VirtualMachine;

/**
 * Class to test Calculator class
 */ 
final class CalculatorTest extends TestCase{


    public function testCalculator(){

        /**
         * @var ServerManager $serverManager
         */
        $serverManager = new ServerManager();

        /**
         * @var Calculator $calculator
         */
        $calculator = new Calculator($serverManager);

        /**
         * @var ServerMachine $serverMachine
         */
        $serverMachine = new ServerMachine();
        $serverMachine->setResource(2, 32, 100);
        
        /**
         * Adds virtual machines
         * @var VirtualMachine[] $vitualMachine
         */
        $virtualMachines[] = $this->createVirtualMachine(1, 16, 10);
        $virtualMachines[] = $this->createVirtualMachine(1, 16, 10);
        $virtualMachines[] = $this->createVirtualMachine(2, 16, 100);

        $serverNeeded = $calculator->calculate( $serverMachine, $virtualMachines);

        // 2 servers needed
        $this->assertEquals(2, $serverNeeded);

    }

    // Tests differents kind of configuration.
    // Validates if the number of servers is returning correctly
    public function testFirstFitAlgorithm(): void
    {

        /**
         * @var ServerManager $serverManager
         */
    	$serverManager = new ServerManager();

        /**
         * @var Calculator $calculator
         */
    	$calculator = new Calculator($serverManager);

        /**
         * @var ServerMachine $serverMachine
         */
    	$serverMachine = new ServerMachine();
    	$serverMachine->setResource(2, 32, 100);
    	
        /**
         * Adds virtual machines
         * @var VirtualMachine[] $vitualMachine
         */
    	$virtualMachines[] = $this->createVirtualMachine(1, 32, 100);
    	$virtualMachines[] = $this->createVirtualMachine(1, 32, 10);
        $virtualMachines[] = $this->createVirtualMachine(2, 16, 10);
        $virtualMachines[] = $this->createVirtualMachine(2, 16, 100);

        /**
         * @var int $serversNeeded
         */
    	$serversNeeded = $calculator->calculate( $serverMachine, $virtualMachines);

    	// 4 servers 
    	$this->assertEquals(4, $serversNeeded);

        $serverMachine = new ServerMachine();
        $serverMachine->setResource(100, 100, 100);
        // test first first
        $virtualMachines = [];
        $virtualMachines[] = $this->createVirtualMachine(30, 10, 10);
        $virtualMachines[] = $this->createVirtualMachine(80, 10, 10);
        $virtualMachines[] = $this->createVirtualMachine(30, 10, 10);

        $serverNeeded = $calculator->calculate( $serverMachine, $virtualMachines);

        // 3 servers
        $this->assertEquals(3, $serverNeeded);        

    }

    // Method to create virtual machines
    private function createVirtualMachine(int $cpu, int $ram, int $hdd): VirtualMachine {
    	
    	$virtualMachine = new VirtualMachine();
    	$virtualMachine->setResource($cpu, $ram, $hdd);

    	return $virtualMachine;
    }

    // Tests if NoVirtualMachineException is thrown when we don't pass the virtual machines
    public function testNoVirtualMachine(): void {

    	$serverManager = new ServerManager();
    	$calculator = new calculator($serverManager);

    	$serverMachine = new ServerMachine();
    	$serverMachine->setResource(2, 32, 100);
    	
    	$virtualMachines = [];
    	try{
    		$serverNeeded = $calculator->calculate( $serverMachine, $virtualMachines);
    	}catch(NoVirtualMachineException $e){
    		$this->assertEquals(NoVirtualMachineException::ERROR_NO_VIRTUAL_MACHINE, $e->getCode());
    	}

    }

}