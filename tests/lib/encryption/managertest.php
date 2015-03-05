<?php

namespace Test\Encryption;

use OC\Encryption\KeyStorage;
use OC\Encryption\Manager;
use Test\TestCase;

class ManagerTest extends TestCase {

	public function testManagerIsDisabled() {
		$config = $this->getMock('\OCP\IConfig');
		$m = new Manager($config);
		$this->assertFalse($m->isEnabled());
	}

	public function testManagerIsDisabledIfEnabledButNoModules() {
		$config = $this->getMock('\OCP\IConfig');
		$config->expects($this->any())->method('getSystemValue')->willReturn(true);
		$m = new Manager($config);
		$this->assertFalse($m->isEnabled());
	}

	public function testManagerIsDisabledIfDisabledButModules() {
		$config = $this->getMock('\OCP\IConfig');
		$config->expects($this->any())->method('getSystemValue')->willReturn(false);
		$em = $this->getMock('\OCP\Encryption\IEncryptionModule');
		$em->expects($this->any())->method('getId')->willReturn(0);
		$em->expects($this->any())->method('getDisplayName')->willReturn('TestDummyModule0');
		$m = new Manager($config);
		$m->registerEncryptionModule($em);
		$this->assertFalse($m->isEnabled());
	}

	public function testManagerIsEnabled() {
		$config = $this->getMock('\OCP\IConfig');
		$config->expects($this->any())->method('getSystemValue')->willReturn(true);
		$em = $this->getMock('\OCP\Encryption\IEncryptionModule');
		$em->expects($this->any())->method('getId')->willReturn(0);
		$em->expects($this->any())->method('getDisplayName')->willReturn('TestDummyModule0');
		$m = new Manager($config);
		$m->registerEncryptionModule($em);
		$this->assertTrue($m->isEnabled());
	}
}
