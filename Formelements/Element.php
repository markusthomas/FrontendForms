<?php
namespace FrontendForms;

/**
* The Element class is a general class for each HTML element that can be created via the Tag class.
* It contains general methods for all HTML elements but cannot be instantiated
*/

/**
 * Class and Function List:
 * Function list:
 * - __construct()
 * - wrap()
 * - removeWrap()
 * Classes list:
 * - Element extends Tag
 */

abstract class Element extends Tag  {

  protected $wrapper; // wrapper object

  public function __construct($id = null)
  {
    parent::__construct();
    if(is_string($id))
      $this->setAttribute('id', $id); // set id if it was set inside the constructor
  }

  /**
  * Add a wrapper arount an element (tag)
  * By default it is a div container, but you can change it to whatever you want
  * @return Wrapper - returns a wrapper object
  */
  public function wrap(): Wrapper
  {
    $this->wrapper = new Wrapper();
    return $this->wrapper;
  }

  /**
  * Remove a wrapper if it is present
  */
  public function removeWrap()
  {
    $this->wrapper = null;
  }

  /**
  * Returns the wrapper object
  */
  public function getWrap(): Wrapper
  {
    return $this->wrapper;
  }


}
