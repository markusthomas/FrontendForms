# FrontendForms
A module for ProcessWire to create and validate forms on the frontend easily using the [Valitron](https://github.com/vlucas/valitron) library.

## Highlights
1. Simple form creation
2. 40+ validation types
3. Support for UiKit 3 and Bootstrap 5 CSS framework
4. SPAM protection
5. Highly customizable
6. Hookable methods for further customization
7. Multi-language
8. Use HTML email templates if you are sending emails with your forms

## Table of contents
* [Quick-start guide](#quick-start-guide)
* [Which validation rules are ready to use](#which-validation-rules-are-ready-to-use)
* [Which input types are supported?](#which-input-types-are-supported)
* [SPAM protection](#spam-protection)
* [Prevent double form submission](#prevent-double-form-submission)
* [Module configuration settings](#module-configuration-settings)
* [Support for UiKit 3 and Boostrap 5](#support-for-uikit-3-and-boostrap-5)
* [General methods](#general-methods)
* [Form and its methods](#form-and-its-methods)
* [Input fields and their methods](#input-fields-and-their-methods)
* [Customization of validation](#customization-of-validation)
* [Fieldsets](#fieldsets)
* [Buttons](#buttons)
* [Hooking](#hooking)
* [Multi-language](#multi-language)
* [HTML email templates](#email-templates)

## Quick-start guide
1. Download and extract FrontendForms and put the folder inside site/modules. Be aware that the folder name must be FrontendForms.
2. Login to your admin area and refresh all modules.
3. Now you can grab this module and install it.
4. After the installation is finished you can change some configuration settings if you want, but for now it is not really necessary.
5. Include the module in your project by putting these 2 lines of code fe inside your _init.php inside your template folder, so you can use FrontendForms across your site. This works only if you set $config->prependTemplateFile = '_init.php' inside your /site/config.php.
By the way you can include these 2 lines also inside the template file where you want to create your form, but it is not the recommended way.

```php
$frontendforms = new FrontendForms();
$frontendforms->setLang('de');
```

Line 1 loads the module with all its classes and on line 2 (optional) you can define a language (default is English) for the pre-defined error messages.
Please take a look inside the folder lang of this module which languages are available. These files contain pre-defined error messages in each language for the different validation types, but you can also set your custom error messages if you want (read more at the customization part later on).
Now you are ready to use the module in any template file.

6. Copy the following code and paste it in a template of your choice

```php
$form = new Form('contactform');

$gender = new Select('gender');
$gender->setLabel('Gender');
$gender->addOption('Mister', 'Mister');
$gender->addOption('Miss', 'Miss');
$form->add($gender);

$surname = new InputText('surname');
$surname->setLabel('Surname');
$surname->setRule('required');
$form->add($surname);

$name = new InputText('lastname');
$name->setLabel('Last Name');
$name->setRule('required');
$form->add($name);

$email = new InputText('email');
$email->setLabel('E-Mail');
$email->setRule('required');
$email->setRule('email');
$form->add($email);

$subject = new InputText('subject');
$subject->setLabel('Subject');
$subject->setRule('required');
$form->add($subject);

$message = new Textarea('message');
$message->setLabel('Message');
$message->setRule('required');
$form->add($message);

$privacy = new InputCheckbox('privacy');
$privacy->setLabel('I accept the privacy policy');
$privacy->setRule('required')->setCustomMessage('You have to accept our privacy policy');
$form->add($privacy);

$button = new Button('submit');
$button->setAttribute('value', 'Send');
$form->add($button);

if($form->isValid()){

  print_r($form->getValues());
  // do what you want

}

// render the form
echo $form->render();

```
### Short Explanation step by step
* As you can see first you have to create a new form object. Inside the constructor you have to write the id of the form (required)
* After that you have to add each form field to the form. Each form field must have a name attribute inside the constructor (required).
* You can set various properties to each form field (setLabel(), setNotes(), setDescription();setRule(), setSanitizer(),...)
* Use the add method to add the field to the form object.
* The isValid() method returns true or false and you can use it to send fe the values as an email or save values to the database, to login a user,....you got the idea.
The validation and sanitization of the form values happens inside this method.
* The render method outputs the markup of the form.

I highly recommend you to study the examples inside the 'examples' folder. There you will find a lot of different usecases.

Now you are ready to test the module inside your project!

## Which validation rules are ready to use
Please take a look at [Valitron validationtypes](https://github.com/vlucas/valitron#built-in-validation-rules) for all available validation rules. There you will find the explanation to each validation rule.
In addition, I have added 7 custom validation rules especially for ProcessWire:

* **uniqueUsername**\
Checks if an username is used by another user or not - useful for user registration form.

* **matchUsername**\
Has to be added to the password field; checks if password and username matches - useful for login form.

* **meetsPasswordConditions**\
Has to be added to the password field; checks if password meets the required conditions set in the backend - useful for registration form.

* **usernameSyntax**\
Checks if the entered username only contains a-z0-9-_. characters - useful for registration or profile form.

* **uniqueEmail**\
Checks if an email address is used by another user or not - useful for registration and profile form.

* **checkPasswordOfUser**\
This validation rule is for logged in users only. Idea: If you want to change your password you have to enter the old password before.
And for that reason I have created this rule. So this rule is for a password field where you have to enter the current password for security reasons - useful for the profile form.

* **matchEmail**\
Has to be added to the password field; checks if password and email matches - useful for login form.
It is the same validation as matchUsername, but in this case you can use email and password for the login.

* **isBooleanAndTrue**\
You can check if a value is from type boolean and true.

* **isBooleanAndFalse**\
You can check if a value is from type boolean and false.

* **exactValue**\
You can check if a value entered inside a textfield is exactly the same value as a value given.

* **differentValue**\
You can check if a value entered inside a textfield is different from a value given.

* **checkTfaCode**\
This is a special method for the login process if you are using TfaEmail component. It check if the code sent by the TfaEmail module is correct.

* **differentPassword**\
This validation checks if the password is different than the old password stored inside the database. Useful if a user wants to change his password.


Maybe other custom validation rules will be added in the future. If you have some ideas, please write a pull request.

Inside the folder 'examples' you will find examples of the usage of validation rules inside the validationTypes.php. Take a look at these examples on how to write and add validation rules to your input fields. You can use as much validators for a field as you need.

### Custom validation rules
It is also possible to create your own custom validation rules and use them inside your forms. On the Valitron Github page you can find more information about how to create rules on your own.
You have to write your custom rules inside your init.php after the module integration. For demonstration purposes I will show you how to add a custom rule named allFail. This rule makes no sense and returns always false if you enter a value inside the input, where you have added it.

```php
$forms = new FrontendForms();
$forms->setLang('de');

$forms->Validator::addRule('allFail', function ($field, $value, array $params) {
  return false;
}, 'is wrong');
```
## Which input types are supported?
You can find examples of all supported input types inside the 'examples' folder in the inputTypes.php

* Inputs ('text, color, date, datetime, email, hidden, month, number, password, range, search, tel, time, url, week)
* Radio button (single)
* Radio buttons (multiple)
* Checkbox (single)
* Checkbox (multiple)
* Select (single)
* Select (multiple)
* Textarea
* Datalist


## SPAM protection
There are multiple traps for spammers included.

### Honeypot field
A honeypot field, which changes the position on every page load in random order, will be added automatically by default. If you do not want to include the honeypot field you need to add the disableHoneypot() method to you form object (not recommended).
Info: A honeypot field is a field which is hidden to the user, but a SPAM bot can read it and if this field will be filled out it will be detected as spam.
Please note that the honeypot field is visible by default. You have to hide it with CSS.
Here is an example CSS to hide the honeypot field:

```css
.seca {
  opacity: 0;
  position: absolute;
  top: 0;
  left: 0;
  height: 0;
  width: 0;
  z-index: -1;
}
```

### Time measurement
You can set a min and max time for filling out the form. You only have to add the setMinTime() and/or setMaxTime() method(s) to your form object. If a user or a SPAM bot submits the form outside this time range, the form will not be submitted.
SPAM bots tend to fill out forms very quickly or analyse the forms very long and submit them after a long while. So with this time trap you can detect SPAM bots.

### Set max number of invalid attempts
You can set a number of max attempts for submitting the form successfully by adding the setMaxAttempts() method to your form object.
If the number of unsuccessful attempts is higher than the allowed number the form submission will be blocked. It is only a soft block with the help of a session. The user will be prompted to close the browser to remove the session and to open the page once more. If the session is active, the form will not be displayed on the page and therefore cannot be filled out. SPAM bots fill out inputfields sometimes randomly and therefore make a lot of mistakes.

## Prevent double form submission
Only to mention: There is also a session active which prevents double form submission after successful validation. It compares the session value with the value of a hidden field. If the values are different, it is an indication that the form would be submitted twice. In this case the submission will be stopped before it takes place and you will be redirected to the form page itself.

## Module configuration settings
At the backend there are a lot of options for global settings. Fe you can choose if you want to add a wrapper container to the inputfield or not or if you want to add a outer wrapper to the complete formfield (including label, inputfield, description, notes,...).
Nearly each CSS class for the various form elements can be overwritten too. So you can use your own prefered class names if you want - no Hooks are necessary.
Take a look at the configuration page - all different settings are described there.

## Support for UiKit 3 and Bootstrap 5
In the backend you can select if you want to render the markup with UiKit 3, Bootstrap 5 or no framework.

## General methods
General methods are methods that can be used on each object: form, input field, label, description, notes, wrappers, fieldset,...

### setAttribute()
You can add every attribute to an object by adding the attribute name and the value inside the parenthesis separated by a comma.

```php
  $field->setAttribute('id', 'myId');
```

For boolean attributes (like disabled, required,...) you only need to add the value.

```php
  $field->setAttribute('disabled');
```

For attributes that can have multiple values like classes, rel, style please write it in the way you write it in HTML, if you want to add more values at once or write it in each line.

```php
  // write in one line
  $field->setAttribute('class', 'class1 class2 class3');
  $field->setAttribute('style', 'color:yellow;font-weight:bold');

  // or write it on a separate line
  $field->setAttribute('class', 'class1');
  $field->setAttribute('class', 'class2');
  $field->setAttribute('class', 'class3');
```

### setAttributes()
You can also set multiple attributes at once, but you have to put the attributes inside an array.

```php
  $field->setAttributes(['id' => 'myId', 'class' => 'myClass']);
```

### removetAttribute()
You can remove an attribute by adding the attribute name inside the parenthesis. In this case you will remove the attribute completely.

```php
  $field->removeAttribute('class'); // this removes the class attribute completely from the tag
```

### removetAttributeValue()
You can also remove a specific value of an attribute and not the attribute itself. This is only useful if you want to remove a value from a multivalue attribute (like the class attribute)

```php
  $field->removeAttributeValue('class', 'class1'); // this removes only the value class1 from the class attribute. All other class values stay untouched
```

### prepend(), append()
You can prepend/append a string before and after an object. So you can add additional markup if you want.

```php
  $field->prepend('<div class="grid">')->append('</div>');  
```

## Form and its methods

The form object holds all the inputfields, fieldsets, additional markup,...

```php
  $form = new Form('myForm');
```

### Methods for the form

#### addFieldWrapper(), removeFieldWrapper(), getFieldWrapper()
Add, remove or get the most outer container for form fields.
The addFieldWrapper() and removeFieldWrapper() method overwrites the settings in the module configuration.
The getFieldWrapper() method returns the Fieldwrapper object, so you can manipulate it, if you need.

```php
$form = new Form('myForm');
$form->addFieldWrapper(); // add the field wrapper to all input elements
$form->removeFieldWrapper(); // remove the field wrapper from all input elements
$form->getFieldWrapper()->setAttribute('class', 'newClass')->removeAttribute('class', 'oldClass'); // customize the wrapper object
```
#### addInputWrapper(), removeInputWrapper(), getInputWrapper()
Add, remove or get the wrapper around the input field.
The addInputWrapper() and removeInputWrapper() method overwrites the settings in the module configuration.
The getInputWrapper() method returns the Inputwrapper object, so you can manipulate it, if you need.

```php
$form = new Form('myForm');
$form->addInputWrapper(); // add the field wrapper to all input elements
$form->removeInputWrapper(); // remove the field wrapper from all input elements
$form->getInputWrapper()->setAttribute('class', 'newClass')->removeAttribute('class', 'oldClass'); // customize the wrapper object
```

#### disableHoneypot()
This will remove the honeypot field from the form, but it is not recommended.

```php
  $form->disableHoneypot();
```

#### setRequiredText()
With this method you can overwrite the position of the required text in the global settings in the backend. As parameter you have none, top or bottom. If set to top, the text will be displayed above the form, otherwise below. If you choose none, than the text will not be displayed at all.

```php
  $form->setRequiredText('bottom');
```

#### setMethod()
Set the form method (post, get). If you want to use post as your method, you do not need to add this method explicitely, because this method was set as the default method.

```php
  $form->setMethod('post');
```
#### setMinTime(), setMaxTime()

Set the min and max time for form submission in seconds. The form will only be submitted if the submission time is in between the time range.

```php
  $form->setMinTime(5);
  $form->setMaxTime(3600);
```
#### setMaxAttempts()

Set the max number of attempts to submit a form successful. If the number of unsuccessful attempts is higher than the max number of attempts, the form submission will be blocked.

```php
  $form->setMaxAttempts(10);
```

#### getValues()
This method returns all form values after successful validation as an array. Use this method to process the values further (fe send via email).
```php
  $form->getValues();
```
#### getValue()
This will return the value of a specific input field after a successful form submission. You have to write the name of the input field inside the parenthesis.
```php
  $form->getValue('subject'); // this will return the value of the input field with the name attribute subject
```
#### add()
This is the method to add a field to the form.
```php
  $form->add($field);
```

#### getFormelementByName()
Grab a form element by its name attribute - returns the field object for further manipulation.
Fe if you want to get the field with the name attribute "email" add "email" as parameter inside the parenthesis and you will get the form field object as return value.
```php
  $form->getFormelementByName($fieldname); // fieldname could be fe email, pass or whatever
```

#### setErrorMsg()
With this method you can overwrite the default error message which appears inside the alert box after a unsuccessful form submission.
```php
  $form->setErrorMsg('Sorry, but there are errors!');
```
#### setSuccessMsg()
With this method you can overwrite the default success message which appears inside the alert box after a successful form submission.
```php
  $form->setSuccessMsg('Congratulations, your message was submitted successfully!');
```

#### isValid()
This is the most important method. It takes the user data, sanitizes it, validates it and outputs possible errors or the success message.
Returns true or false after form submission. You have to use this method to process the submitted form data further.
```php
  $form->isValid();
```

#### isBlocked()

If you want to do another logic if a user was blocked, then use the isBlocked() method and run your code inside it.

```php
  $form = new Form();
  $form->setMaxAttempts(10);
  ....
  if($form->isBlocked()){
    .....
  }
  $form->render()
```
#### render()
Render the form on the page.
```php
  $form->render();
```
## Input fields and their methods

### Anatomy of input fields

```html
<div class="uk-margin" id="validationform-test1-fieldwrapper"> <!-- This is the field wrapper -->
  <label class="uk-form-label required" for="validationform-test1">Test required<span class="asterisk">*</span></label> <!-- The label -->
  <div class="uk-form-controls" id="validationform-test1-inputwrapper"> <!-- This is the input wrapper -->
    <input id="validationform-test1" name="test1" type="text" class="uk-input" required=""> <!-- The input field -->
    <p class"uk-text-error">This would be the error text on validation errors</p> <!-- The error message text -->
  </div>
  <p class="notes">Field is required</p> <!-- The notes text -->
  <p class="description">This is my field description</p> <!-- The field description text -->
 </div>
```

#### Field wrapper
The field wrapper is the most outer container. You can enable/disable it in the global settings in the backend. But you can overwrite the global settings on each form individually by using the addFieldwrapper() or removeFieldwrapper method at the form object.
If you want to customize the field wrapper you can use the getFieldWrapper() method which returns the field wrapper object itself.

#### Input wrapper
This is a container element around the input field. You can set or remove it in the same way as the field wrapper by using the addInputWrapper(), removeInputWrapper() and getInputWrapper() methods.

#### Label, notes, description
These elements do not need a more detailed explanation. Only to mention here: you can customize all of them by chaining methods to set/remove attributes.

### General Methods for input fields
These methods can be used on each input field.

#### setLabel()

Method to add a label to the form field. Returns a label object.

```php
$field->setLabel('E-Mail address');
```

#### setNotes()

Method to add notes to the form field. Returns a notes object.

```php
$field->setNotes('You have to fill out this field');
```

#### setDescription()

Method to add a description to the form field. Returns a description object.

```php
$field->setDescription('This text describes the input field more in detail');
```

#### setSanitizer()

Method to add a sanitizer to the form field. Returns a sanitizer object. You can use all ProcessWire sanitizer methods by adding the sanitizer name inside the parenthesis of the setSanitizer() method. You can also set multiple sanitizer methods to one field if necessary.
Please note: For security reasons, the text sanitizer will be applied to each inputfield automatically, so you do not have to add it manually. The only exception is input textarea, where a textarea sanitizer will be applied by default.

```php
$field->setSanitizer('text');
```

#### removeSanitizers()
You can remove all sanitizers (including the sanitizers applied by default) with this method, but it is recommended that you sanitize user input.

```php
$field->removeSanitizers();
```

#### setRule()

Method to add a validator to the form field. You can find examples of all validators in the validationTypes.php inside the 'examples' folder. Add the name of the validator  inside the parenthesis of the setRule() method. You can also set multiple validation methods to one field if necessary.

```php
$field->setRule('required');
$field->setRule('number');
```

#### removeRule()
This is the opposite of setRule(). You can remove unwanted rules with this method. This is useful if you use pre-defined Inputs, which contains some validation rules by default.

```php
$field->removeRule('required');
```

#### getErrorMessage()
You can use this method to manipulate attributes of the error message on per field base.
```php
$field->getErrorMessage()->setAttribute('class', 'myErrorClass');
```

#### setDefaultValue()
Set pre-defined values on page load to each input field. If it is an input field that can contain more than 1 value (fe select multiple, checkbox multiple), you can add multiple values separated by a comma.

```php
$singlefield = new InputText('name')
$singlefield->setDefaultValue('John');
```

```php
$multifield = new InputCheckboxMultiple('hobbies')
$multifield->setDefaultValue('Tennis', 'Polo', 'Swimming');
```

#### render()
Method to render an input field. You do not need this method until you want to render an input field on its own.
```php
$field->render();
```

### Special Methods for input fields
These methods can only be used on certain input fields.

#### alignVertical() for checkboxes and radio buttons
This is only a method for multiple checkboxes and radio buttons to align them vertical instead of horizontal.

```php
$checkbox = new InputCheckboxMultiple('myCheckbox')
$checkbox->alignVertical();
```

#### addOption() for checkboxes, radio buttons, select and datalist
Method for multiple checkboxes, multiple radio buttons, select and datalist inputs to add an option element. As parameters, you have to add the label as first and the value as second parameter. Afterwards is an example with a multiple checkbox.

```php
$checkbox = new InputCheckboxMultiple('myCheckbox');
$checkbox->addOption('Checkbox 1', '1');
$checkbox->addOption('Checkbox 2', '2');
```

#### setChecked() for checkboxes and radio buttons
This is an alternative method to set default values for checkboxes or radio buttons.
Using this method is necessary and the only way to mark checkboxes or radio buttons without a value attribute. If a checkbox fe has no value, it is impossible to set a default value. If a value attribute is present, you can use the setDefault() method instead.

```php
$checkbox = new Checkbox('singlecheckbox')
$checkbox->setChecked();

$checkboxmulti = new InputCheckboxMultiple('multicheckbox');
$checkboxmulti->addOption('Checkbox 1', 1)->setChecked();
$checkboxmulti->addOption('Checkbox 2', 2)->setChecked();
$checkboxmulti->addOption('Checkbox 3', 3);
```

#### setSelected() for selects and datalists
This is an alternative method to set default values for option elements of select/datalist elements.

```php
$select = new InputSelectMultiple('multiselect');
$select->addOption('Select 1', 1)->setSelected();
$select->addOption('Checkbox 2', 2)->setSelected();
$select->addOption('Checkbox 3', 3);
```

#### showPasswordRequirements() for password fields
This method can be added to a password field and shows the password conditions set on the password field in the backend (fe has to contain digit uppercase, digit lowercase,...) under the input field.
By default the requirements of the password field with the name "pass" will be shown. If your password field has another name, please insert the name as parameter to the method.

```php
$password1 = new InputPassword('password1');
$password1->showPasswordRequirements(); // the values from the field "pass" will be used by default
```
```php
$password2 = new InputPassword('password2');
$password2->showPasswordRequirements('test'); // the values from the field "test" will be used
```

#### showPasswordToggle() for password fields
This method can be added to a password field and adds a checkbox below the input field. If the checkbox will be checked, the password entered will be displayed in plain text, otherwise not.
JavaScript is used to show/hide the password in plain text.

```php
$password = new InputPassword('password');
$password->showPasswordToggle();
```

## Customization of validation
For each validator, there is a pre-defined error message inside the lang folder. This is ok for most cases, but sometimes you need to show another error message than the pre-defined one. For these cases you can customize your error messages with 2 methods.

Default error message:
By default the error message uses the name of the input field and prepends it before the error message

```php
$field = new InputCheckbox('privacy')
$field->seRule('required');
```
If the validation fails, the error message will look like this:
Privacy is required

If you do not want that the name of the field (in this case privacy) should be used, then you can change this in the following way:

### setCustomFieldName()
By using the setCustomFieldName() method you can change the name of the input field in the error message

```php
$field = new InputCheckbox('privacy')
$field->seRule('required')->setCustomFieldName('This field');
```
If the validation fails, the error message will look like this:
This field is required

### setCustomMessage()
Use this method if you want to overwrite the default error message completely.

```php
$field = new InputCheckbox('privacy')
$field->seRule('required')->setCustomMessage('You must accept our privacy policy');
```
If the validation fails, the error message will look like this:
You must accept our privacy policy

## Fieldsets
You can also add fieldsets and a legend to the form.

```php
$fieldsetStart = new FieldsetOpen();
$fieldsetStart->setLegend('My legend');
$form->add($fieldsetStart);
...
...
$fieldsetEnd = new FieldsetClose();
$form->add($fieldsetEnd);
```

## Buttons
Here is an example of a form button.

```php
$buttonReset = new Button('reset');
$buttonReset->setAttribute('type','reset');
$buttonReset->setAttribute('value', 'Reset');
$form->add($buttonReset);
```

## Hooking
Hooking is not really necessary in most cases, because you have so much configuration options to achieve your desired result. Anyway, if there is a need for it, every method with 3 underscores is hookable.

### Hook example 1: Change the asterisk markup via a Hook
If you are not satisfied with the markup for the asterisk on required fields, you can use the following Hook inside your init.php to create your own markup.

Before:
```html
<span class="asterisk">*</span>
```
Hook function

```php
$wire->addHookAfter('Label::renderAsterisk', function(HookEvent $event) {
  $event->return = '<span class="myAsterisk">+</span>';
});
```
After:
```html
<span class="myAsterisk">+</span>
```

### Hook example 2: Add Font Awesome exclamation sign in front of the error message

Before:
```html
<p class="uk-text-error">This is the error message.</p>
```
Hook function

```php
$wire->addHookAfter('Errormessage::render', function(HookEvent $event) {
  $alert = $event->object;
  $fontAwesome = '<i class="fas fa-exclamation-triangle"></i>';
  $alertText = $alert->getText();
  $alert->setText($fontAwesome.$alertText);
  $event->return = $alert->___render();
});
```
After:
```html
<p class="uk-text-error"><i class="fas fa-exclamation-triangle"></i>This is the error message.</p>
```
## Multi-language
This module supports multi-language. All text strings are fully translateable in the backend The default language is English.

### Using translation files from languages folder
If you are using ProcessWire version 3.0.181 or higher, you can take advantage of the new feature of using CSV files for different languages. All other versions below do not support this feature.
The folder languages includes translation files for the following languages at the moment:

* German

Maybe other language files will be added in the future.

#### How to install the module language files in ProcessWire
After you have installed the module go to the configuration page of the module.
There you will find inside the module info a new item called Languages. Beside this there will be a link to install the existing language files. Click the link, choose the correct file for your language and press the save button.
Now the language files shipped with this module are installed and you did not have to translate the strings by yourself.
Only to mention: If the language files will be updated, you have to install them once more. They will not be updated automatically

## Email templates

In most cases forms are used to send data via emails (fe a simple contact form).
ProcessWire is shipped with the WireMail class to send emails.
Unfortunately this class does not support the usage of stylish HTML email templates by default, so I have decided to enhance this class with a new method to simply choose an email template, which is stored inside the email_templates folder.

### New method mailTemplate()
First of all you need to know, that inside the email_templates folder you will find folders and PHP files with various names (fe template_1.php, template_2.php).
These names are the one you can use as the parameter within the brackets of this method:

```php
$mail = new WireMail();
$mail->mailTemplate('template_1'); // this adds the template with the file template_1.php - note: remove the extension php
```
These files are email templates and they contain placeholder for your content.
Fe the text of the subject will be rendered inside the placeholder {SUBJECT},
the text of the body inside the placeholder {BODY}.
It is recommended to take a look at the email templates which are shipped with this module. You can take them as an example on how to write your own email templates (or download free templates from the internet and add the placeholders by yourself).

If you have created a template add it to the email_templates folder as the other templates and now you are ready to use it.

Using this method renders your email as a stylish HTML email using the selected email template.

### Following Placeholders are supported by default for the usage in HTML email template

* {TITLE} : Renders the value of the $mail->title inside the template.
* {SUBJECT} : Renders the value of the $mail->subject inside the template.
* {BODY} : Renders the value of the $mail->body inside the template.
* {USERNAME} : Renders the value of the username inside the template, if the user is logged in.
* {DOMAIN}: Renders the domain of the site (fe http://www.mysite.com)

#### New method title()

The title method adds a title attribute to the HTML template which will be displayed under the subject. You can also output it inside your template with the placeholder {TITLE}

```php
$mail = new WireMail();
$mail->title('This is my title');
```

#### Add and output a custom placeholder inside your email template with addPlaceholder() method

The addPlaceholder() method consists of 2 parameters: placeholder name and placeholder value.

```php
$mail = new WireMail();
$mail->addPlaceholder('date', '01.01.2022');
```
The placeholder is always the name in uppercase letters and inside brackets: {DATE}
With the example above you can use the placeholder {DATE} inside your mail template to output '01.01.2022' inside the template (in this case).
