<?php

namespace Test\Encryption;

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
		$m = new Manager($config);
		$this->assertTrue($m->isEnabled());
	}

	/**
	 * @expectedException \OC\Encryption\Exceptions\ModuleAlreadyExistsException
	 * @expectedExceptionMessage Id "0" already used by encryption module "TestDummyModule0"
	 */
	public function testModuleRegistration() {
		$config = $this->getMock('\OCP\IConfig');
		$config->expects($this->any())->method('getSystemValue')->willReturn(true);
		$em = $this->getMock('\OCP\Encryption\IEncryptionModule');
		$em->expects($this->any())->method('getId')->willReturn(0);
		$em->expects($this->any())->method('getDisplayName')->willReturn('TestDummyModule0');
		$m = new Manager($config);
		$m->registerEncryptionModule($em);
		$this->assertTrue($m->isEnabled());
		$m->registerEncryptionModule($em);
	}

	public function testModuleUnRegistration() {
		$config = $this->getMock('\OCP\IConfig');
		$config->expects($this->any())->method('getSystemValue')->willReturn(true);
		$em = $this->getMock('\OCP\Encryption\IEncryptionModule');
		$em->expects($this->any())->method('getId')->willReturn(0);
		$em->expects($this->any())->method('getDisplayName')->willReturn('TestDummyModule0');
		$m = new Manager($config);
		$m->registerEncryptionModule($em);
		$this->assertTrue($m->isEnabled());
		$m->unregisterEncryptionModule($em);
		$this->assertFalse($m->isEnabled());
	}

	/**
	 * @expectedException \OC\Encryption\Exceptions\ModuleDoesNotExistsException
	 * @expectedExceptionMessage Module with id: unknown does not exists.
	 */
	public function testGetEncryptionModuleUnknown() {
		$config = $this->getMock('\OCP\IConfig');
		$config->expects($this->any())->method('getSystemValue')->willReturn(true);
		$em = $this->getMock('\OCP\Encryption\IEncryptionModule');
		$em->expects($this->any())->method('getId')->willReturn(0);
		$em->expects($this->any())->method('getDisplayName')->willReturn('TestDummyModule0');
		$m = new Manager($config);
		$m->registerEncryptionModule($em);
		$this->assertTrue($m->isEnabled());
		$m->getEncryptionModule('unknown');
	}

	public function testGetEncryptionModule() {
		$config = $this->getMock('\OCP\IConfig');
		$config->expects($this->any())->method('getSystemValue')->willReturn(true);
		$em = $this->getMock('\OCP\Encryption\IEncryptionModule');
		$em->expects($this->any())->method('getId')->willReturn(0);
		$em->expects($this->any())->method('getDisplayName')->willReturn('TestDummyModule0');
		$m = new Manager($config);
		$m->registerEncryptionModule($em);
		$this->assertTrue($m->isEnabled());
		$en0 = $m->getEncryptionModule(0);
		$this->assertEquals(0, $en0->getId());
	}
}
