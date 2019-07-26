<?php

use PHPUnit\Framework\TestCase;
use ServerAmount\Calculator;
use ServerAmount\Machine\ServerMachine;
use ServerAmount\Machine\VirtualMachine;
use ServerAmount\Exceptions\NoVirtualMachineException;
use ServerAmount\Exceptions\InvalidResourceException;
use ServerAmount\Exceptions\InvalidVirtualMachineException;

/**
 * Class to test Calculator class
 */ 
final class CalculatorTest extends TestCase{


    public function testThreeVirtualMachinesInTwoServers(){

        /**
         * @var ServerMachine $serverMachine
         */
        $serverMachine = new ServerMachine();
        $serverMachine->setResource(2, 32, 100);

        /**
         * @var Calculator $calculator
         */
        $calculator = new Calculator();

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

    public function testVirtualMachinesSameSizeAsServer(){

        /**
         * @var ServerMachine $serverMachine
         */
        $serverMachine = new ServerMachine();
        $serverMachine->setResource(2, 32, 100);

        /**
         * @var Calculator $calculator
         */
        $calculator = new Calculator();

        /**
         * Adds virtual machines
         * @var VirtualMachine[] $vitualMachine
         */
        $virtualMachines[] = $this->createVirtualMachine(2, 32, 100);
        $virtualMachines[] = $this->createVirtualMachine(2, 32, 100);
        $virtualMachines[] = $this->createVirtualMachine(2, 32, 100);

        $serverNeeded = $calculator->calculate( $serverMachine, $virtualMachines);

        // 2 servers needed
        $this->assertEquals(3, $serverNeeded);

    }

    public function testFirstFitAlgorithmBigServer(): void
    {
        /**
         * @var ServerMachine $serverMachine
         */
        $serverMachine = new ServerMachine();
        $serverMachine->setResource(100, 100, 100);

        /**
         * @var Calculator $calculator
         */
        $calculator = new Calculator();
        
        // test first first
        $virtualMachines[] = $this->createVirtualMachine(30, 10, 10);
        $virtualMachines[] = $this->createVirtualMachine(80, 10, 10);
        $virtualMachines[] = $this->createVirtualMachine(30, 10, 10);

        $serverNeeded = $calculator->calculate( $serverMachine, $virtualMachines);

        // 3 servers
        $this->assertEquals(3, $serverNeeded);        

    }

    public function testInvalidResourceException(): void
    {

        $this->expectException(InvalidResourceException::class);

        /**
         * @var ServerMachine $serverMachine
         */
        $serverMachine = new ServerMachine();
        $serverMachine->setResource(0, 0, 0);

        /**
         * @var Calculator $calculator
         */
        $calculator = new Calculator();
        
        // test first first
        $virtualMachines[] = $this->createVirtualMachine(30, 10, 10);
        $virtualMachines[] = $this->createVirtualMachine(80, 10, 10);
        $virtualMachines[] = $this->createVirtualMachine(30, 10, 10);

        $calculator->calculate( $serverMachine, $virtualMachines);

    }

    public function provideVirtualMachineBiggerThanServer(){
        return [
            [$this->createVirtualMachine(2, 16, 10)], // Cpu bigger
            [$this->createVirtualMachine(1, 32, 10)], // Ram bigger
            [$this->createVirtualMachine(1, 16, 200)], // HDD Bigger
        ];
    }

    /**
     * @dataProvider provideVirtualMachineBiggerThanServer
     */
    public function testVirtualMachineBiggerThanServer(VirtualMachine $machine){

        $this->expectException(InvalidVirtualMachineException::class);

        /**
         * @var ServerMachine $serverMachine
         */
        $serverMachine = new ServerMachine();
        $serverMachine->setResource(1, 16, 100);

        /**
         * @var Calculator $calculator
         */
        $calculator = new Calculator();
        
        $calculator->calculate( $serverMachine, [$machine]);

    }

    public function provideVirtualMachineEmptyResource(){
        return [
            [$this->createVirtualMachine(0, 16, 10)], // Cpu 0
            [$this->createVirtualMachine(1, 0, 10)], // Ram 0
            [$this->createVirtualMachine(1, 16, 0)], // HDD 0
            [$this->createVirtualMachine(0, 0, 0)], // All empty
            [$this->createVirtualMachine(0, 0, 10)], // CPU and RAM 0
            [$this->createVirtualMachine(1, 0, 0)], // HDD and RAM 0
            [$this->createVirtualMachine(0, 16, 0)], // HDD and CPU 0
        ];
    }

    /**
     * @dataProvider provideVirtualMachineEmptyResource
     */
    public function testVirtualMachineEmptyResource(VirtualMachine $machine): void
    {

        $this->expectException(InvalidVirtualMachineException::class);

        /**
         * @var ServerMachine $serverMachine
         */
        $serverMachine = new ServerMachine();
        $serverMachine->setResource(1, 16, 100);

        /**
         * @var Calculator $calculator
         */
        $calculator = new Calculator();
        
        $calculator->calculate( $serverMachine, [$machine]);
        
    }

    public function testFirstFitAlgorithmBasicMachine(): void
    {

        /**
         * @var ServerMachine $serverMachine
         */
        $serverMachine = new ServerMachine();
        $serverMachine->setResource(2, 32, 100);

        /**
         * @var Calculator $calculator
         */
    	$calculator = new Calculator();
    	
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

    }

    private function createVirtualMachine(int $cpu, int $ram, int $hdd): VirtualMachine {
    	
    	$virtualMachine = new VirtualMachine();
    	$virtualMachine->setResource($cpu, $ram, $hdd);

    	return $virtualMachine;
    }

    public function testNoVirtualMachineCrashes(): void {

        /**
         * @var ServerMachine $serverMachine
         */
        $serverMachine = new ServerMachine();
        $serverMachine->setResource(2, 32, 100);
        
        /**
         * @var Calculator $calculator
         */
    	$calculator = new Calculator();

    	$virtualMachines = [];
    	try{
    		$serverNeeded = $calculator->calculate( $serverMachine, $virtualMachines);
    	}catch(NoVirtualMachineException $e){
    		$this->assertEquals(NoVirtualMachineException::ERROR_NO_VIRTUAL_MACHINE, $e->getCode());
    	}

    }

}