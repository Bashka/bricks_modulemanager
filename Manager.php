<?php
namespace Bricks\ModuleManager;

/**
 * Загружает и инициирует модули приложения.
 *
 * @author Artur Sh. Mamedbekov
 */
class Manager{
  /**
   * @var string Адрес хранилища модулей (каталога).
   */
  private $store;

  /**
   * @var string[] Имена загружаемых модулей.
   */
  private $modulesNames;

  /**
   * @param string $store Адрес хранилища модулей (каталога).
   * @param array $modules [optional] Массив имен загружаемых из хранилища 
   * модулей в порядке их загрузки. Если параметр не передан, будут загружены 
   * все найденные в хранилище модули (в алфавитном порядке по возрастанию).
   */
  public function __construct($store, array $modules = null){
		$this->store = $store;
    if(is_null($modules)){
      $this->modulesNames = array_diff(scandir($store), ['..', '.']);
    }
    else{
      $this->modulesNames = $modules;
    }
  }

  /**
    * Загружает и инициализирует модули.
    *
    * @param callable|string Имя вызываемого у модуля метода, которому будут 
    * переданы данные из второго аргумента метода.
    * Так же может использоваться анонимная функция, вызываемая для каждого 
    * модуля, которой будут переданы следующие параметры:
    *   - Экземпляр модуля
    *   - Адрес каталога, содержащего модуль
    *   - Данные, переданные во втором параметре
    * @param array $args [optional] Массив параметров, передаваемых в метод 
    * инициализации модуля.
    *
    * @return array Массив инициализированных экземпляров модулей.
   */
  public function init($initiator, array $args = []){
    $modules = [];
    foreach($this->modulesNames as $moduleName){
      $moduleAddress = $this->store . DIRECTORY_SEPARATOR . $moduleName;
      $classAddress = $moduleAddress . DIRECTORY_SEPARATOR . 'Module.php';
      if(!is_file($classAddress)){
        continue;
      }
      include($classAddress);
      $className = $moduleName . '\Module';
      $module = new $className;

      if(is_callable($initiator)){
        call_user_func_array($initiator, [$module, $moduleName, $moduleAddress, $args]);
      }
      else{
        call_user_func_array([$module, $initiator], $args);
      }
      array_push($modules, $module);
    }

    return $modules;
  }
}
