<?php

namespace FrontendForms;

use ProcessWire\WireInputData as WireInputData;
use ProcessWire\Password as Password;

use function ProcessWire\_n;

/**
 * Class for creating a form
 */

/**
 * Class and Function List:
 * Function list:
 * - __construct()
 * - setMinTime()
 * - getMinTime()
 * - setMaxTime()
 * - getMaxTime()
 * - setMaxAttempts()
 * - getMaxAttempts()
 * - getValues()
 * - getValue()
 * - add()
 * - getNamesOfInputFields()
 * - getRequiredText()
 * - setRequiredText()
 * - renderRequiredText()
 * - getAlert()
 * - setSuccessMsg()
 * - getSuccessMsg()
 * - setErrorMsg()
 * - getErrorMsg()
 * - createHoneypot()
 * - checkDoubleFormSubmission()
 * - removeInputWrapper()
 * - addInputWrapper()
 * - removeFieldWrapper()
 * - addFieldWrapper()
 * - secondsToReadable()
 * - checkMaxAttempts()
 * - disableHoneypot()
 * - setValues()
 * - encrypt_decrypt()
 * - checkTimeDiff()
 * - thisFormSubmitted()
 * - isValid()
 * - isBlocked()
 * - render()
 * Classes list:
 * - Form extends Tag
 */

class Form extends Tag
{
    const FORMMETHODS = ['get', 'post'];
    // allowed methods for form submission

    // Alert texts
    protected $alertSuccessText = '';
    // alert text after successfull submission
    protected $alertErrorText = '';
    // alert text if errors occur after submission

    // Text hint that all required fields have to be filled out
    protected $defaultRequiredTextPosition = 'top';
    // Property for do not reply to automatically generated emails
    protected $doNotReply = '';
    protected $formElements = [];
    //array of form element objects
    protected $formErrors = [];
    // holds the array containing all form errors after submission

    protected $disableHoneypot = false;
    // Honeypotfield is enabled => true or disabled => false

    protected $removeInputWrapper = false;
    // Set or remove input wrapper for all form fields in general
    protected $removeFieldWrapper = false;
    // Set or remove field wrapper for all form fields in general

    protected $values = [];
    // array of all form values (key = name of the inputfield)

    protected $minTime = null;
    // min time that should be pass by before the form is submitted (spam protected) - default is null (no restriction)
    protected $maxTime = null;
    // max time until the form should be submitted (spam protected) - default is null (no restriction)
    protected $maxAttempts = null;
    // max number of attempts allowed until the form should be submitted valid

    // Classes
    protected $alert;
    protected $requiredHint;
    protected $valitron;
    protected $showForm = true;
    // show the form on the page
    protected $doubleSubmissionToken = '';
    // the token for checking of double form submission; will be removed if form was valid

  /**
   * Every form must have an id, so lets add it via the constructor
   * The id will be taken for further automatic id generation of the inputfields
   */
    public function __construct(string $id)
    {
        parent::__construct();
        $this->setAttribute('method', 'post');
        // default is post
        $this->setAttribute('action', $this->wire('page')->url);
        // set the current page as default target after form submission
        $this->setAttribute('id', $id);
        // set the id
        $this->setAttribute('novalidate');
        // set novalidate by default
        $this->setTag('form');
        // set the form tag
        $this->setCSSClass('formClass');
        $this->alert = new Alert();
        // instantiate alert class
      // Set default messages for success and failure - can be overwritten
        $this->setSuccessMsg($this->_('Thank you for your message.'));
        // set default success message
        $this->setErrorMsg($this->_('Sorry, some errors occur. Please check your inputs once more.'));
        // set default error message
        $this->requiredHint = new RequiredTextHint();
        // Set default hint text for required fields
        $this->requiredHint->setText($this->_('All fields marked with (*) are mandatory and must be completed.'));
        // create unique session for checking for double form submission if it was not created
        if (!$this->wire('session')->get('doubleSubmission')) {
            $this->doubleSubmission = uniqid();
            $this->wire('session')->set('doubleSubmission', $this->doubleSubmission);
        } else {
        // the session exist, so lets add it to the property
                $this->doubleSubmission = $this->wire('session')->get('doubleSubmission');
        }

        // set global settings from module configuration
        $this->removeInputWrapper = isset($this->moduleConfig['input_wrappers'][0]) ? true : false;
        $this->removeFieldWrapper = isset($this->moduleConfig['input_wrappers'][1]) ? true : false;
        $this->defaultRequiredTextPosition = $this->moduleConfig['input_requiredHintPosition'];
        $this->doNotReply = $this->_('This email is automatically generated, therefore please do not reply.');
    }

  /***********
   * Various methods for code creations, query strings and others.
   */

  /**
   * Generate a slug out of a string for usage in urls (fe querystrings)
   * This is only a helper function
   * @param $string - the string
   * @return string
   */
    public function generateSlug(string $string): string
    {
        $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
        return $slug;
    }


  /**
   * Create a random string with a certain lenght for usage in URL query strings
   * @param int $charLength - the length of the random string - default is 100
   * @return string - returns a slug version of the generated random string that can be used inside an url
   */
    protected function createQueryCode(int $charLength = 100)
    {
        $pass = new Password();
        // instantiate a password object to use the methods
        $string = $pass->randomBase64String($charLength);
        return $this->generateSlug($string);
    }



  /**
   * Set the min time in seconds before the form should be submitted
   * @param int $minTime
   */
    public function setMinTime(int $minTime)
    {
        $this->minTime = $minTime;
    }

  /**
   * Get the min time value
   * @return int|null
   */
    protected function getMinTime(): ?int
    {
        return $this->minTime;
    }

  /**
   * Set the max time in seconds until the form should be submitted
   * @param int $maxTime
   */
    public function setMaxTime(int $maxTime)
    {
        $this->maxTime = $maxTime;
    }

  /**
   * Get the max time value
   * @return int|null
   */
    protected function getMaxTime(): ?int
    {
        return $this->maxTime;
    }

  /**
   * Set the max attempts
   * @param int $maxAttempts
   */
    public function setMaxAttempts(int $maxAttempts)
    {
        $this->maxAttempts = $maxAttempts;
    }

  /**
   * Get the max attempts
   * @return int|null
   */
    protected function getMaxAttempts(): ?int
    {
        return $this->maxAttempts;
    }

  /**
   * Get all form values after valid form submission as an array
   * @return array
   */
    public function getValues(): array
    {
        return $this->values;
    }

  /**
   * Get the value of a specific formfield after valid form submission by its name
   * Can be used to send fe this value via email to a recipient
   * @param string $name - the name attribute of the input field
   * @return string|null
   */
    public function getValue(string $name): ?string
    {
        $name = trim($name);
        return $this->getValues()[$name];
    }


  /** Append a field object to the form
   * @param object $field - object of inputfield, fieldset, button,...
   */
    public function add(object $field)
    {
        // add or remove wrapper divs on each form element
        if (is_subclass_of($field, 'FrontendForms\Inputfields')) {
            $this->removeInputWrapper ? $field->removeInputWrapper() : $field->addInputWrapper();
            $this->removeFieldWrapper ? $field->removeFieldWrapper() : $field->addFieldWrapper();
        }
        $this->formElements = array_merge($this->formElements, [$field]); // array must be numeric for honeypot field
    }


  /**
   * Get a specific element of the form by entering the name of the element as parameter
   * With this method you can grab and manipulate a specific element
   * @param string $name - the name attribute of the element (fe email)
   * @return object - the form element object
   */
    public function getFormelementByName(string $name)
    {
        return current(array_filter($this->formElements, function ($e) use ($name) {
            return $e->getAttribute('name') == $name;
        }));
    }

  /**
   * Return the names of all inputfields inside a form as an array
   * @return array
   */
    protected function getNamesOfInputFields(): array
    {
        $elements = [];
        if (count($this->formElements)) {
            foreach ($this->formElements as $element) {
                if (is_subclass_of($element, 'FrontendForms\Inputfields')) {
                    $elements[] = $element->getAttribute('name');
                }
            }
        }
        return array_filter($elements);
    }

  /**********************/
  /* Required text hint */
  /**********************/

  /**
   * Get the required text hint object for further manipulations
   * @return RequiredTextHint
   */
    public function getRequiredText(): RequiredTextHint
    {
        return $this->requiredHint;
    }


  /**
   * Overwrite the global setting for the required text position on per form base
   * @param string $position - has to be 'top' or 'bottom'
   */
    public function setRequiredText(string $position)
    {
        $positions = ['none', 'top', 'bottom'];
        $position = trim($position);
        $position = (in_array($position, $positions)) ? $position : 'top';
        $this->defaultRequiredTextPosition = $position;
    }


  /**
   * Create required hint text element if showTextHint is set to true
   * @param string $position - has to be 'top' or 'bottom'
   * @return string|none
   */
    private function renderRequiredText(string $position)
    {
        if ($this->defaultRequiredTextPosition == $position) {
            return $this->requiredHint->render();
        }
    }

  /**************/
  /* Form alert */
  /**************/

  /**
   * Get the alert object for further manipulations
   * @return Alert
   */
    public function getAlert(): Alert
    {
        return $this->alert;
    }

  /**
   * Set the success message for successfull form submission
   * Can be used to overwrite the default success message
   * @param string
   */
    public function setSuccessMsg(string $successMsg)
    {
        $this->alertSuccessText = trim($successMsg);
    }

  /**
   * Get the success message
   * @return string
   */
    protected function getSuccessMsg(): string
    {
        return $this->alertSuccessText;
    }

  /**
   * Set the error message if errors occur after form submission
   * Can be used to overwrite the default error message
   * @param string
   */
    public function setErrorMsg(string $errorMsg)
    {
        $this->alertErrorText = trim($errorMsg);
    }

  /**
   * Get the error message
   * @return string
   */
    protected function getErrorMsg(): string
    {
        return $this->alertErrorText;
    }

    /**
     * Output an error message that email could not be sent due to possible wrong email configuration settings
     * This is a general message that could be used for all forms
     */
    protected function generateEmailSentErrorAlert()
    {
        $this->alert->setCSSClass('alert_dangerClass');
        $this->alert->setText($this->_('Email could not be sent due to possible wrong email configuration settings.'));
    }


  /**
   * Create a honeypot field for spam protection
   * @return InputText
   */
    private function createHoneypot(): InputText
    {
        $honeypot = new InputText('seca');
        $honeypot->setLabel($this->_('Please do not fill out this field'))->setAttribute('class', 'seca');
        // Remove or add wrappers depending on settings
        $this->removeInputWrapper ? $honeypot->removeInputWrapper() : $honeypot->addInputWrapper();
        $this->removeFieldWrapper ? $honeypot->removeFieldWrapper() : $honeypot->addFieldWrapper();
        $honeypot->getFieldWrapper()->setAttribute('class', 'seca');
        $honeypot->getInputWrapper()->setAttribute('class', 'seca');
        $honeypot->setAttributes(['class' => 'seca', 'tabindex' => '-1']);
        return $honeypot;
    }

  /**
   * Check if form is submitted twice after successfull validation
   * @param WireInputData $input
   * @return boolean (true -> form was not submitted twice)
   */
    private function checkDoubleFormSubmission(WireInputData $input): bool
    {
        // assign submitted **secretFormValue** from your form to a local variable
        $secretFormValue = isset($input->doubleSubmission_token) ? filter_var($input->doubleSubmission_token, FILTER_UNSAFE_RAW) : '';
        // check if the value is present in the **secretFormValue** variable
        if ($secretFormValue != '') {
            // check if both values are the same
            if ($this->wire('session')->get('doubleSubmission') == $secretFormValue) {
                return true;
            } else {
                return false;
            }
        } else {
            throw new \Exception("Token value to prevent double form submission is missing", 1);
        }
    }

  /** Public methods to add or remove input wrapper and field wrapper on each form field in general */

  /**
   * Remove the input wrapper from all fields of this form in general
   */
    public function removeInputWrapper()
    {
        $this->removeInputWrapper = true;
    }

  /**
   * Add the input wrapper to all fields of this form in general
   */
    public function addInputWrapper()
    {
        $this->removeInputWrapper = false;
    }

  /**
   * Remove the field wrapper from all fields of this form in general
   */
    public function removeFieldWrapper()
    {
        $this->removeFieldWrapper = true;
    }

  /**
   * Add the input wrapper to all fields of this form in general
   */
    public function addFieldWrapper()
    {
        $this->removeFieldWrapper = false;
    }

  /**
   * Convert seconds to human readable format like 1 minute and 20 seconds instead of 80 seconds
   * @param int $ss - seconds
   * @return string
   */
    private function secondsToReadable(int $ss): string
    {
        $bit = [
         'month' => floor($ss / 2592000),
         'day' => floor(($ss % 2592000) / 86400),
         'hour' => floor(($ss % 86400) / 3600),
         'minute' => floor(($ss % 3600) / 60),
         'second' => $ss % 60
        ];

        $labelSingular = ['month' => $this->_('month'),
         'day' => $this->_('day'),
         'hour' => $this->_('hour'),
         'minute' => $this->_('minute'),
         'second' => $this->_('second')];

        $labelPlural = ['month' => $this->_('months'),
         'day' => $this->_('days'),
         'hour' => $this->_('hours'),
         'minute' => $this->_('minutes'),
         'second' => $this->_('seconds')];

        foreach ($bit as $k => $v) {
            $number = explode(' ', $v);
            if ($number[0] != 0) {
                $label = $this->_n($labelSingular[$k], $labelPlural[$k], $v);
                $ret[] = $v . $label;
            }
        }

        if (count($ret) > 1) {
            array_splice($ret, count($ret) - 1, 0, $this->_('and'));
        }

        return join(' ', $ret);
    }


    /**
     * Check if max attemps are reached or not - true or false
     * Depending on the result, the form will be displayed or not
     * @return boolean -> true if attempts limit is not reached, otherwise false
     */
    protected function checkMaxAttempts(): bool
    {
        if ($this->getMaxAttempts()) {
            if (($this->getMaxAttempts() - $this->wire('session')->attempts) > 0) {
                return true;
            } else {
                $this->wire('session')->set('blocked', 'maxAttempts');
                // set session for blocked
                return false;
            }
        } else {
            return true;
        }
    }


  /**
   * Honeypotfield is enabled by default.
   * If you want to disable it, add this method to the form object - not recommended
   */
    public function disableHoneypot()
    {
        $this->disableHoneypot = true;
    }

  /**
   * Internal method to add all form values to the values array
   */
    private function setValues()
    {
        $values = [];
        foreach ($this->formElements as $element) {
            $values[$element->getAttribute('name')] = $element->getAttribute('value');
        }
        $this->values = $values;
    }

  /**
   * Encrypt/Decrypt Function
   * @param $string - the value that should be encrypted/decrypted
   * @param $action - encrypt or decrypt
   * @return string - the encrypted/decrypted string
   */
    protected function encrypt_decrypt($string, $action = 'encrypt'): string
    {
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'd0a7e7997b6d5fcd55f4b5c32611b87cd923e88837b63bf2941ef819dc8ca282';
        // user define private key
        $secret_iv = '5fgf5HJ5g27';
        // user define secret key
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        // sha256 is hash_hmac_algo
        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } elseif ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }


  /**
   * Check if min time and max time limits are lower/higher than submission time
   * @return boolean - true, if everything is ok
   */
    private function checkTimeDiff(): bool
    {
        if (($this->getMinTime()) || ($this->getMaxTime())) {
            // grab the page_load value
            $method = $this->getAttribute('method');
            $start_time = $this->wire('input')->$method('load_time');
            // check if the inputfield load_time is present in the form
            if ((($this->getMinTime()) || ($this->getMaxTime())) && (!$start_time)) {
                throw new \Exception("Inputfield load_time is not present in the form.", 1);
            } else {
                //get the timestamp value and decrypt and sanitize it
                $start_time = $this->encrypt_decrypt($start_time, 'decrypt');
                $start_Time = $this->wire('sanitizer')->string($start_time);
                $submit_Time = time();
                $diff = $submit_Time - $start_Time;
                $submit_Time = $this->secondsToReadable($diff);
                // to fast
                if ($this->getMinTime() && ($diff < $this->getMinTime())) {
                    $secondsLeft = $this->_('seconds left');
                    $secondLeft = $this->_('second left');
                    $text = sprintf($this->_('You have submitted the form within %s. This seems pretty fast for a human. Your behavior is more similar to a Spam bot. Please wait at least %s until you submit the form once more.'), $submit_Time, '<span id="minTime" data-time="' . $this->getMinTime() . '" data-unit="' . $secondsLeft . ';' . $secondLeft . '">' . $this->secondsToReadable($this->getMinTime())) . '</span><div id="timecounter"></div>';
                    $this->alert->setCSSClass('alert_warningClass');
                    $this->alert->setText($text);
                    return false;
                }

                //to slow
                if ($this->getMaxTime() && ($diff > $this->getMaxTime())) {
                    $this->wire('session')->set('blocked', $submit_Time);
                    // set session for blocked value is the submission time
                    return false;
                }

                return true;
            // submission was in time
            }
        }
        return true;
    }

  /**
   * Check if exactly this form was submitted by checking the form id against the hidden field form_id
   * @param WireInputData $input - the get or post data after submission
   * @return bool - true, if this form was submitted, otherwise false
   */
    private function thisFormSubmitted(WireInputData $input): bool
    {
        if (($input->form_id) && ($this->wire('sanitizer')->string($input->form_id) == $this->getID())) {
            return true;
        }
        return false;
    }

  /**
   * Process the form after form submission
   * Includes sanitization and validation
   * @return bool - true: form is valid, false: form has errors
   */
    public function isValid()
    {

        $formMethod = $this->getAttribute('method');
        // grab the method (get or post)
        $input = $this->wire('input')->$formMethod;
        // get the GET or POST values after submission

        //grab all form elements as an array of objects
        $formElements = $this->formElements;
        $values = [];
        // Loop over all form elements and sanitize values if one or more sanitizer are added before sending it to valitron validation
        foreach ($formElements as $element) {
            $fieldname = $element->getAttribute('name');
            if (array_key_exists($fieldname, $input->getArray())) {
                $value = $input->$fieldname;
            // run sanitizer methods only on inputfields
                if (is_subclass_of($element, 'FrontendForms\Inputfields')) {
                    //sanitize the value first if a sanitizer was set and it is a valid sanitizer
                    foreach ($element->getSanitizer() as $sanitizer) {
                        $value = ($this->wire('sanitizer')->$sanitizer($input->$fieldname));
                    }
                }
                $values[$fieldname] = $value;
                $element->setAttribute('value', $value);
                // add value to element
            }
        }

        // 1) check if this form was submitted and no other form on the same page
        if ($this->thisFormSubmitted($input)) {
            // 2) check if form was submitted in time range
            if ($this->checkTimeDiff()) {
                // 3) check if max attempts were reached
                if ($this->checkMaxAttempts()) {
                    // 4) check for double form submission
                    if ($this->checkDoubleFormSubmission($input)) {
                        // 5) Check for CSRF attack
                        if ($this->wire('session')->CSRF->validate()) {
                          /* START PROCESSING THE FORM */

                          //add honeypotfield to the array because it will be rendered afterwards
                            if (!$this->disableHoneypot) {
                                array_push($formElements, $this->createHoneypot());
                            }

                            // Instantiate Valitron and start validation
                            $v = new \Valitron\Validator($values);
                            $this->valitron = $v;
                            foreach ($formElements as $element) {
                                              //check if element is extending from Inputfields class, because validation can only be done on inputfields
                                if (is_subclass_of($element, 'Frontendforms\Inputfields')) {
                                    // run validation only if there is at least one validation rule set
                                    if (count($element->getRules()) > 0) {
                                        foreach ($element->getRules() as $validatorName => $parameters) {
                                            $v->rule($validatorName, $element->getAttribute('name'), ...$parameters['options']);
                                            // Add custom error message text if present
                                            if (isset($parameters['customMsg'])) {
                                                $v->message($parameters['customMsg']);
                                            }
                                            if (isset($parameters['customFieldName'])) {
                                                $v->label($parameters['customFieldName']);
                                            }
                                        }
                                    }
                                }
                                                // add honeypot validation if honeypot field is included
                                if (!$this->disableHoneypot) {
                                    if ($element->getAttribute('name') == 'seca') {
                                          $v->rule('length', 'seca', 0)->message($this->_('Please do not fill out this field'));
                                          // length must be 0 = empty
                                    }
                                }
                                $this->setValues();
                            }

                            if ($v->validate()) {
                                $this->alert->setCSSClass('alert_successClass');
                                $this->alert->setText($this->getSuccessMsg());
                                $this->wire('session')->remove('attempts');
                                // remove attempt session
                                $this->wire('session')->remove('doubleSubmission');
                                // remove the session for checking for double form submission
                                $this->showForm = false;
                                return true;
                            } else {
                                    // set error alert
                                    $this->formErrors = $v->errors();
                                    $this->alert->setCSSClass('alert_dangerClass');
                                    $this->alert->setText($this->getErrorMsg());
                                    // add max attempts warning message to error message
                                if ($this->getMaxAttempts()) {
                                    $attemptWarningText = '';
                                    $attemptDiff = $this->getMaxAttempts() - $this->wire('session')->attempts;
                                    if ($attemptDiff <= 3) {
                                        $plural = $this->_('attempts');
                                        $singular = $this->_('attempt');
                                        $attempts = $this->_n($singular, $plural, $attemptDiff);
                                        $attemptWarningText = '<br>' . sprintf($this->_('You have %s %s left until you will be blocked due to security reasons.'), $attemptDiff, $attempts);
                                        $this->alert->setText($this->alert->getText() . $attemptWarningText);
                                    }
                                }

                                // create session for max attempts if set, otherwise add 1 attempt.
                                //this session contains the number of failed attempts and will be increased by 1 on each failed attempt

                                if ($this->getMaxAttempts()) {
                                    if ($this->wire('session')->attempts) {
                                                  $this->wire('session')->attempts = $this->wire('session')->attempts + 1;
                                    } else {
                                              $this->wire('session')->attempts = 1;
                                    }
                                }

                                return false;
                            }

                      /* END PROCESSING THE FORM */
                        }
                        // CSRF attack
                        die();
                        // live a great life and die() gracefully.
                    }
                    //double form submission
                    return false;
                }
                //max attempts were reached
                return false;
            }
            // submission time was to short or to long
            return false;
        }
        // this form was not submitted
        return false;
    }


  /**
   * Method to run if a user has taken to much attempts
   * This method has to be before the render method of the form
   * You can use it fe to save some data to the database -> you got the idea
   * @return bool -> returns true if the the user is blocked, otherwise false
   */
    public function isBlocked(): bool
    {
        if ($this->wire('session')->get('blocked')) {
            return true;
        }
        return false;
    }


  /**
   * Render the form markup (inluding alerts if present) on frontend
   * @return string
   */
    public function render(): string
    {

        $out = '';
        if ($this->prepend) {
            $out .= $this->prepend;
        }

        if ($this->append) {
            $out .= $this->append;
        }
        // allow only get or post - if value is not get or post set post as default value
        if (!in_array(strtolower($this->getAttribute('method')), self::FORMMETHODS)) {
            $this->setAttribute('method', 'post');
        }

        // get token for CSRF protection
        $tokenName = $this->wire('session')->CSRF->getTokenName();
        $tokenValue = $this->wire('session')->CSRF->getTokenValue();
        // get keys of all inputfields (excluding buttons, fieldsets,.. only inputfields that collect user data)
        $inputfieldKeys = [];
        foreach ($this->formElements as $key => $inputfield) {
            if (is_subclass_of($inputfield, 'FrontendForms\Inputfields')) {
                $inputfieldKeys[] = $key;
            }
        }

        // Add honeypot field only if at least 1 input field is present
        if (count($inputfieldKeys)) {
            // Choose an inputfield randomly by its key value
            shuffle($inputfieldKeys);
            $randomFieldNumber = $inputfieldKeys[0];
            // add honeypot on the random number field position
            if (!$this->disableHoneypot) {
                array_splice($this->formElements, $randomFieldNumber, 0, [$this->createHoneypot()]);
            }
        }

        //create CSRF hidden field and add it to the form at the end
        $hiddenField = new InputHidden('post_token');
        $hiddenField->setAttribute('name', $tokenName);
        $hiddenField->setAttribute('value', $tokenValue);
        $this->add($hiddenField);
        //create hidden field to prevent double form submission
        $hiddenField2 = new InputHidden('doubleSubmission_token');
        $hiddenField2->setAttribute('name', 'doubleSubmission_token');
        $hiddenField2->setAttribute('value', $this->doubleSubmission);
        $this->add($hiddenField2);
        //create hidden field to send form id to check if this form was submitted
        //this is only there for the case if other forms are present on the same page
        $hiddenField3 = new InputHidden('form_id');
        $hiddenField3->setAttribute('name', 'form_id');
        $hiddenField3->setAttribute('value', $this->getID());
        $this->add($hiddenField3);
        //create hidden field to send the timestamp (encoded) when the form was loaded
        if (($this->getMinTime()) || $this->getMaxTime()) {
            $hiddenField4 = new InputHidden('load_time');
            $hiddenField4->setAttribute('value', $this->encrypt_decrypt(time(), 'encrypt'));
            $this->add($hiddenField4);
        }

      /* BLOCKING ALERTS */
        if ($this->wire('session')->get('blocked')) {
            // set danger alert for blocking messages
            $this->alert->setCSSClass('alert_dangerClass');
            // return blocking text for to much failed attempts
            if ($this->wire('session')->get('blocked') == 'maxAttempts') {
                if ($this->wire('session')->get('attempts') == $this->getMaxAttempts()) {
                          $this->alert->setText($this->_('You have reached the max. number of allowed attempts and therefore you cannot submit the form once more. To reset the blocking and to submit the form anyway you have to close this browser, open it again and visit this page once more.'));
                }
            } else {
                  // return blocking text for to slow submission
                  $text = sprintf($this->_('You have submitted the form after %s. This seems pretty slow for a human. Your behavior is more similar to a Spam bot. Please submit the form within %s the next time. You are blocked now and you have to close the browser to unlock, open it again and visit this page once more.'), $this->wire('session')->get('blocked'), $this->secondsToReadable($this->getMaxTime()));
                  $this->alert->setText($text);
            }
        }

        // Output the form markup
        $out .= $this->alert->render();
        // render the alert box on top for success or error message

        // show form only if user is not blocked
        if (($this->showForm == true) && (($this->wire('session')->get('blocked') == null))) {
            //add required texts
            $this->prepend($this->renderRequiredText('top'));
            // required text hint at top
            $this->append($this->renderRequiredText('bottom'));
            // required text hint at bottom
            $formElements = '';
            foreach ($this->formElements as $element) {
                    //create input ID as a combination of form id and input name
                    $oldId = $element->getAttribute('id');
                    $element->setAttribute('id', $this->getID() . '-' . $oldId);
                    $name = $element->getAttribute('name');
                    // Label (Only on inputfields)
                if (is_subclass_of($element, 'FrontendForms\Inputfields')) {
                    // add unique id to the fieldwrapper if present
                    if ($element->getFieldWrapper()) {
                        $element->getFieldWrapper()->setAttribute('id', $this->getID() . '-' . $oldId . '-fieldwrapper');
                    }
                    // add unique id to the inputwrapper if present
                    if ($element->getInputWrapper()) {
                        $element->getInputWrapper()->setAttribute('id', $this->getID() . '-' . $oldId . '-inputwrapper');
                    }
                    if ($element->getLabel()) {
                        $element->getLabel()->setAttribute('for', $element->getAttribute('id'));
                        //set for attribute for the label tag
                    }
                }
                if (array_key_exists($name, $this->formErrors)) {
                    $element->setCSSClass('input_errorClass');
                    // set error class for input element
                    $element->setErrorMessage($this->formErrors[$name][0]);
                    //get first error message
                }
                    $formElements .= $element->render();
            }

          // render the form with all its fields
            $this->setContent($formElements);
            $out .= $this->renderNonSelfclosingTag($this->getTag());
        }

        return $out;
    }
}
