<?php

namespace ServerAmount\Machine;

/**
 * Trait to set the basic machine configuration
 */
trait BasicMachineTrait{

	private $cpu;
	private $hdd;
	private $ram;

	public function getCpu() : int{
		return $this->cpu;
	}

	public function setCpu(int $cpu) : void{
		$this->cpu = $cpu;
	}
	public function getHdd() : int{
		return $this->hdd;
	}

	public function setHdd(int $hdd) : void{
		$this->hdd = $hdd;
	}
	public function getRam() : int{
		return $this->ram;
	}

	public function setRam(int $ram) : void{
		$this->ram = $ram;
	}

	public function setResource(int $cpu, int $ram, int $hdd): void{
		$this->setCpu($cpu);
		$this->setRam($ram);
		$this->setHdd($hdd);
	}

}