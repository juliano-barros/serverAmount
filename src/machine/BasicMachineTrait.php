<?php

trait BasicMachineTrait{

	private int $cpu;
	private int $hdd;
	private int $ram;

	public function getCpu(){
		return $this->cpu;
	}

	public function setCpu(int $cpu){
		$this->cpu = $cpu;
	}
	public function getHdd(){
		return $this->hdd;
	}

	public function setHdd(int $hdd){
		$this->hdd = $hdd;
	}
	public function getRam(){
		return $this->ram;
	}

	public function setRam(int $ram){
		$this->ram = $ram;
	}

}