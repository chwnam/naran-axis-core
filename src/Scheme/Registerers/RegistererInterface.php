<?php


namespace Naran\Axis\Core\Scheme\Registerers;

interface RegistererInterface {
	public function registerItems();

	public function unregisterItems();

	public function getItems();
}
