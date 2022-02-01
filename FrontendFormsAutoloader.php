<?php
namespace ProcessWire;

/**
* Class for autoloading php classes in different directories
*/

class FrontendFormsAutoloader
{
    private $directory_name = null;
    private $module_name = null;

    /**
    * @param string|null $directory_name -> the name of the directory
    */
    public function __construct($directory_name = null, $module_name = null)
    {
          $this->directory_name = $directory_name;
          $this->module_name = $module_name;
    }

    /**
    * Include classes with require_once
    * @param string $class_name -> the name of the class which should be included
    */
    public function autoload(string $class_name)
    {
      $array = explode('\\', $class_name);
      $class_name = (end($array)); // get class name without namespace
        $name = $this->module_name;
        $rootPath = wire('config')->paths->$name.DIRECTORY_SEPARATOR;
        $file_name = $class_name.'.php';

        if($this->directory_name) {
          $subdir = $this->directory_name.DIRECTORY_SEPARATOR;
        } else {
          $subdir = '';
        }

        $file = $rootPath.$subdir.$file_name;

        if (file_exists($file) == false)
        {
            return false;
        }
        require_once($file);
    }
}
