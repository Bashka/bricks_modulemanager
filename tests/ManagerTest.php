<?php
namespace Bricks\ModuleManager;
require('Manager.php');

/**
 * @author Artur Sh. Mamedbekov
 */
class ManagerTest extends \PHPUnit_Framework_TestCase{
  /**
   * @var Manager Менеджер модулей.
	 */
	private $manager;

	public function setUp(){
    $this->manager = new Manager('tests/store');
  }

  /**
   * Должен загружать модули и вызывать метод инициализации.
   */
  public function testInit(){
    $this->manager->init('init');
  }
}
