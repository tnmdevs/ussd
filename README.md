# TNM TruRoute USSD Adapter - Laravel 

This package creates an adapter, boilerplate code and functionality that lets you interact with USSDC and offer USSD channel to your API. This adapter was specifically developed to interact with TruRoute USSD Interface. 

* Disclaimer: There is no guarantee that this adapter will work with any other USSD interface unless it exposes the same interface as the one we developed for.

## Installation
```
composer require tnmdev/ussd
```


Then install the ussd scaffold. This will also run migrations to create session tracking tables

```
php artisan ussd:install
```
Once you install the package, the USSD app will be accessible on `/api/ussd` endpoint. A landing screen will be created for you at `App\Screens\Welcome.php`. 

## Usage

### 1.  Creating USSD Screens

```
php artisan make:ussd <name>
```
This will create a boilerplate USSD screen object for you. You can go ahead and edit the contents of `message`, `options` and `execute` methods. The screen extends `TNM\USSD\Screen` class which gives you means of accessing the request details, and encoding USSD response.

### 2. The `Request` object

`Screen` has `$request` as a public property. This is an object of `TNM\USSD\Http\Request` class.

The request class exposes four properties from the xml request passed on by USSDC. 

| Property | Description |
| ---------| ------------- |
| message | The message passed from USSD |
| type | Integer value representing the type of request |
| session | USSD session ID |
| msisdn | The number making the USSD request |

The USSD screen that is sent to the user is represented by `Screens` which extend the `TNM\USSD\Screen` class. 

### 3. Request Payload

You can move payload from between screens using request payload. Any piece of data added to a request payload can be accessed by other request within the session.

#### Setting request payload
Request payload can be added by calling `addPayload` method on request's trail object. It takes a key-value pair of parameters. 
```php
$this->request->trail->addPayload('phone', $this->getRequestValue());
```
This method is also delegated in the screen object as 
```php
$this->addPayload('key', 'value');
```
#### Retrieving request payload
```php
$this->request->trail->payload('phone');
```
This is also delegated as 
```php
$this->payload('key');
```

### 4. The Mandatory Methods
The `Screen` class will require you to implement the following methods.
* `message()` must return a string message that will be displayed on the screen.
* `options()` must return an array of options which will be exposed to the user. Return an empty array for screens that require no options.
* `execute()` this should be used to implement whatever the app should do with request data. The request data is returned by `getRequestValue()` within the screen object. You may use that to access the request data. If you want to redirect the user to another screen, return the `render()` method of the target screen: `return (new Register($this->request))->render()`. The Screen initialization takes one argument, the `request` object.
* `previous()` this should return an object of the `Screen` class. It tells the session where to navigate to when the user chooses the back option.
### 5. Optional Methods
You can extend the following methods to change some properties of the screen.
* `type()` should return an integer delegated to constants `RELEASE` and `RESPONSE` of the `TNM\USSD\Response` class. It defaults to `RESPONSE` if not overridden. `RESPONSE` renders a screen with an input field, while `RELEASE` renders a screen without an input field, used to instruct the USSD Gateway to close the USSD session.
* `acceptsResponse()`, instead of the complexity of `type()` method, you can call `acceptsResponse()`. It should return a boolean which instructs the screen whether to render an input field or to send a screen that marks the end of the USSD session.
* `goesBack()` return a boolean value defining if the screen should have a `back` navigation option. You can leave it alone unless you are defining the landing screen.

### 6. Exception Handling
The USSD adapter has a self-rendering exception handler. To use it, `throw new UssdException` of the `TNM\USSD\Exceptions` namespace. It takes two params: the `request` object and the message you want to pass to the user. The exception handler renders a USSD screen with the error message and terminates the session.

### 7. Input Data Validation
You can set rules to validate the user input by using `Validates` trait of the `TNM\USSD\Http` namespace.
The trail will require you to implement `rules()` method, which should return a string of validation rules. 

To validate input, call `$this->validate($this->request, $label)` in `execute()` method of your `Screen` class.

If the input has a validation error, `ValidationException` of the `TNM\USSD\Exceptions` namespace will be thrown and an error screen will be rendered for you automatically.

```php
namespace App\Screens;

use TNM\USSD\Screen;
use TNM\USSD\Http\Validates;

class EnterPhoneNumber extends Screen
{
    use Validates;

    protected function message() : string
    {
        return 'Enter your phone number';
    }
    
    protected function options() : array
    {
        return [];
    }
    
    public function previous() : Screen
    {
        return new Welcome($this->request);
    }

    protected function execute()
    {
        $this->validate($this->request, 'phone');
        // proceed with implementation
    }

    protected function rules() : string
    {
        return 'regex:/(088)[0-9]{7}/';
    }
}      
```

### Example Screen Implementation

```php
// app/Screens/Subscribe.php

namespace App\Screens;

use TNM\USSD\Screen;

class Subscribe extends Screen
{
    public function message(): string
    {
        return "Please select a service you want to subscribe to";
    }

    public function options(): array
    {
        return ['Service 1', 'Service 2', 'Service 3'];
    }

    public function execute()
    {
        // save the request value to session object 
        // to access it in the next screen with $this->payload($key) 
        $this->addPayload('service', $this->getRequestValue());

        return (new ConfirmSubscription($this->request))->render();
    }
        
    public function previous(): Screen
    {
        return new Welcome($this->request);
    }
}
```

```php
// app/Screens/ConfirmSubscription.php

namespace App\Screens;

use TNM\USSD\Screen;
use TNM\USSD\Exceptions\UssdException;

class ConfirmSubscription extends Screen
{
    public function message(): string
    {
        return sprintf("Please confirm subscription to %s", $this->payload('service'));
    }

    public function options(): array
    {
        return ['Confirm', 'Cancel'];
    }

    public function execute()
    {
        if ($this->getRequestValue() === 'Cancel') return $this->previous()->render();
        
        $service = new SubscriptionService();

        try {
        
            $service->subscribe($this->payload('service'), $this->request->msisdn);
            return (new Subscribed($this->request))->render();
            
        } catch (\Exception $exception) {
            throw new UssdException($this->request, "Subscription failed. Please try again later");
        }
    }
    
    public function previous(): Screen
    {
        return new Subscribe($this->request);
    }
}
```
