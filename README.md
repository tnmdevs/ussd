# TNM USSD Adapter

This package creates an adapter, boilerplate code and functionality that lets you interact with USSDC and offer USSD 
channel to your API. The interface is open and documentated for implementation with various USSD interfaces. 

  * [Installation](#installation)
  * [Usage](#usage)
    + [Creating USSD Screens](#creating-ussd-screens)
    + [The `Request` object](#the--request--object)
    + [Request Payload](#request-payload)
      - [Setting request payload](#setting-request-payload)
      - [Retrieving request payload](#retrieving-request-payload)
      - [Using Arrays in Payload](#using-arrays-in-payload)
    + [The Mandatory Methods](#the-mandatory-methods)
    + [Optional Methods](#optional-methods)
    + [Exception Handling](#exception-handling)
    + [Input Data Validation](#input-data-validation)
    + [Extending for Multiple Implementations](#extending-for-multiple-implementations)
      - [Example Request Implementation](#example-request-implementation)
        * [Required methods](#required-methods)
      - [Example Response Implementation](#example-response-implementation)
      - [Routing](#routing)
        * [Sample Request Factory](#sample-request-factory)
        * [Sample Response Factory](#sample-response-factory)
    + [Localization](#localization)
    + [Audit](#audit)
    + [Session Data CleanUp](#session-data-cleanup)
    + [Example Screen Implementation](#example-screen-implementation)

<small><i><a href='http://ecotrust-canada.github.io/markdown-toc/'>Table of contents generated with markdown-toc</a></i></small>

## Installation

```
composer require tnmdev/ussd
```


Then install the ussd scaffold. This will also run migrations to create session tracking tables

```
php artisan ussd:install
```
Once you install the package, the USSD app will be accessible on `/api/ussd` endpoint. A landing screen will be created 
for you at `App\Screens\Welcome.php`. 

## Usage

### Creating USSD Screens

```
php artisan make:ussd <name>
```
This will create a boilerplate USSD screen object for you. You can go ahead and edit the contents of `message`, 
`options` and `execute` methods. The screen extends `TNM\USSD\Screen` class which gives you means of accessing the 
request details, and encoding USSD response.

### The `Request` object

`Screen` has `$request` as a public property. This is an object of `TNM\USSD\Http\Request` class.

The request class exposes four properties from the xml request passed on by USSDC. 

| Property | Description |
| ---------| ------------- |
| message | The message passed from USSD |
| type | Integer value representing the type of request |
| session | USSD session ID |
| msisdn | The number making the USSD request |

The USSD screen that is sent to the user is represented by `Screens` which extend the `TNM\USSD\Screen` class. 

### Request Payload

You can move payload from between screens using request payload. Any piece of data added to a request payload can be 
accessed by other request within the session.

#### Setting request payload
Request payload can be added by calling `addPayload` method on request's trail object. It takes a key-value pair of 
parameters. 
```php
$$this->addPayload('key', $this->value());
```
#### Retrieving request payload
```php
$this->payload('key');
```

#### Using Arrays in Payload
There are times where you have associative arrays for options. For example, you can have a list of products, 
with `id`, `price`, `name` and `humanized`. Where name is what the product is referred to as in your system, and 
`humanized` is how you want it to appear on the screen.

An array of such items can be pushed to payload with a third boolean parameter. This tells the trail object to
serialize the input before storing it. 
```php
$this->addPayload('products', $array, true);
```  

Manipulating array payloads is made possible by `HasBundledOptions` trait of `TNM\USSD\Traits` namespace. So to use
arrays in your payload, you need to use `HasBundledOptions` trait in your `Screen`.

Here are some of the uses of the bundled options trait: to list/map an associative array as USSD options, you can `map` 
to the array key of your choice using the `map` method.
```php
public function options(): array 
{
    return $this->map('humanized', 'products');
}
```
The `map` method takes two arguments. First is the array key you want to map with, and the second is the payload key you
want to list from.

When the user makes an option on the USSD screen, you can map back to any key of the associative array option made by 
calling the `find` method.
```php
$this->addPayload('chosenProduct', $this->find('id', 'products'));
```
The implementation in the snippet above, will assign the `ID` of the chosen product to a payload key `chosenProduct`. 
The trait looks for the user's option passed as the second argument. You can specify the field to look in by passing
a third argument, which defaults to `humanized`. So the assumption is that your options associative array will have a
field for displaying the content. You can rename it to anything that suits you. Just make sure you pass a third 
argument to tell the method where to look. 

In other cases you may just want to fetch the whole array on a particular payload key. The method is the same as a 
normal payload, again with a second boolean argument.
```php
$this->payload('products', true);
```

### The Mandatory Methods
The `Screen` class will require you to implement the following methods.
* `message()` must return a string message that will be displayed on the screen.
* `options()` must return an array of options which will be exposed to the user. Return an empty array for screens that 
require no options.
* `execute()` this should be used to implement whatever the app should do with request data. The request data is 
returned by `getRequestValue()` within the screen object. You may use that to access the request data. If you want to 
redirect the user to another screen, return the `render()` method of the target screen: 
`return (new Register($this->request))->render()`. The Screen initialization takes one argument, the `request` object.
* `previous()` this should return an object of the `Screen` class. It tells the session where to navigate to when the 
user chooses the back option.
### Optional Methods
You can extend the following methods to change some properties of the screen.
* `type()` should return an integer delegated to constants `RELEASE` and `RESPONSE` of the `TNM\USSD\Response` class. 
It defaults to `RESPONSE` if not overridden. `RESPONSE` renders a screen with an input field, while `RELEASE` renders a
 screen without an input field, used to instruct the USSD Gateway to close the USSD session.
* `acceptsResponse()`, instead of the complexity of `type()` method, you can call `acceptsResponse()`. It should return
 a boolean which instructs the screen whether to render an input field or to send a screen that marks the end of the 
 USSD session.
* `goesBack()` return a boolean value defining if the screen should have a `back` navigation option. You can leave it 
alone unless you are defining the landing screen.

### Exception Handling
The USSD adapter has a self-rendering exception handler. To use it, `throw new UssdException` of the 
`TNM\USSD\Exceptions` namespace. It takes two params: the `request` object and the message you want to pass to the 
user. The exception handler renders a USSD screen with the error message and terminates the session.

### Input Data Validation
You can set rules to validate the user input by using `Validates` trait of the `TNM\USSD\Http` namespace.
The trail will require you to implement `rules()` method, which should return a string of validation rules. 

To validate input, call `$this->validate($this->request, $label)` in `execute()` method of your `Screen` class.

If the input has a validation error, `ValidationException` of the `TNM\USSD\Exceptions` namespace will be thrown and an 
error screen will be rendered for you automatically.

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
    
    //...
    
    protected function execute()
    {
        $this->validate($this->request, 'phone');
        $this->addPayload('phone', $this->value());
        return (new NextScreen($this->request))->render();
    }

    protected function rules() : string
    {
        return 'regex:/(088)[0-9]{7}/';
    }
}      
```

### Extending for Multiple Implementations

This adapter was designed with extendability in mind. Right now it supports TruRoute and Flares USSD interfaces used by 
TNM and Airtel Malawi respectively. However, with the pluggable interface, it can be extended to support any mobile 
network operator.

To extend, create a request and response class. These classes must implement the `TNM\USSD\Http\UssdRequestInterface` 
and `TNM\USSD\Http\UssdResponseInterface` respectively.

Implementation details of the request class may vary. However, we strongly recommend having a constructor that decodes
the USSD request from the mobile operator into an array that should be assigned to `$request` private property and have 
the interface methods return their values based on the private property.

#### Example Request Implementation
```php
use TNM\USSD\Http\UssdRequestInterface;

class TruRouteRequest implements UssdRequestInterface
{
    /**
     * @var array
     */
    private $request;

    public function __construct()
    {
        $this->request = json_decode(json_encode(simplexml_load_string(request()->getContent())), true);
    }

    public function getMsisdn(): string
    {
        return $this->request['msisdn'];
    }
    // ...
}
```
##### Required methods
The request interface requires you to implement the following methods: 
* `getSession()` should return the session `id` assigned by the USSD gateway
* `getMsisdn()` should return the msisdn making a ussd request
* `getMessage()` should return the message sent with the request
* `getType()` should return the type of request.

#### Example Response Implementation

The following is an example response class implementation. It has one required public method: `respond` which must 
return a message in a format required by the network operator. 
```php
use TNM\USSD\Http\UssdResponseInterface;

use TNM\USSD\Screen;

class TruRouteResponse implements UssdResponseInterface
{
    public function respond(Screen $screen)
    {
        return sprintf(
            "<ussd><type>%s</type><msg>%s</msg><premium><cost>0</cost><ref>NULL</ref></premium></ussd>",
            $screen->type(), $screen->getResponseMessage()
        );
    }
}
```
#### Routing
You can distinguish requests from different mobile operators using a route parameter `adapter`.   

All requests from a network that uses `Flares` adapter should be routed to `api/ussd/flares`. So when you create your 
own extension, the route for the operator should be `api/ussd/{adapter}`. 

This is not resolved magically. You are required to define the implementation in `TNM\USSD\Factories\RequestFactory` and 
`TNM\USSD\Factories\ResponseFactory`

##### Sample Request Factory
```php
namespace TNM\USSD\Factories\RequestFactory;

class RequestFactory
{
    public function make(): UssdRequestInterface
    {
        switch (request()->route('adapter')) {
            case 'flares' :
                return resolve(FlaresRequest::class);
            default:
                return resolve(TruRouteRequest::class);
        }
    }
}
```
##### Sample Response Factory
```php
namespace TNM\USSD\Factories\ResponseFactory;

class ResponseFactory
{
    public function make(): UssdResponseInterface
    {
        switch (request()->route('adapter')) {
            case 'flares':
                return resolve(FlaresResponse::class);
            default:
                return resolve(TruRouteResponse::class);
        }
    }
}
```

### Localization

You can set the session language in any screen of the application. The following screen will come in the newly selected 
language. 

```php
$this->request->trail->setLocale('en');
```

This feature implements the Laravel's localization with language files. Refer to the Laravel docs for more detail.
So your implementation can be like the following:

```php
public function message(): string 
{
    return __("screens.welcome_message");
}
```
#### Sample Localization Implementation
```php
public function execute()
{
    $locale = $this->value() == 'English' ? 'en' : 'fr';
    $this->request->trail->setLocale($locale);
    return (new NextScreen($this->request))->render();
}
```

### Audit

You can track user sessions, system messages and user responses with a CLI tool.

```
php artisan ussd:list <phone>
```
This command gives you a list of all the transactions that were done by a number. The list contains session ID and 
timestamp.

```
php artisan ussd:audit <session-id>
```
This command gives you all the details of the transaction from the beginning of a session to the end. The trail includes
system messages, user responses to each message and their timestamps in a chronological order. 

When a user response was an option, it reports a string value that is represented by the number that was selected, saving you 
from having to lookup which option was on number 1, 2 or etc.

### Session Data CleanUp

The package keeps track of sessions using a database table. This database table may need to clean-up after some time.
To clean up run the following command in the application directory.
```bash
php artisan ussd:clean-up --days=30
```

It takes the option of number of days' data to preserve. If no option is passed, it deletes everything older than 60 days.

* A note on audit: An audit trail will not be available for the data that has been cleaned up.

### Example Screen Implementation

```php
// app/Screens/Subscribe.php

namespace App\Screens;

use TNM\USSD\Screen;

class Subscribe extends Screen
{
    public function message(): string
    {
        return "Please select a plan you want to subscribe to";
    }

    public function options(): array
    {
        return ['Plan 1', 'Plan 2', 'Plan 3'];
    }

    public function execute()
    {
        // save the request value to session object 
        // to access it in the next screen with $this->payload($key) 
        $this->addPayload('plan', $this->value());

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

use Exception;use TNM\USSD\Screen;
use TNM\USSD\Exceptions\UssdException;

class ConfirmSubscription extends Screen
{
    public function message(): string
    {
        return sprintf("Please confirm subscription to %s", $this->payload('plan'));
    }

    public function options(): array
    {
        return ['Confirm', 'Cancel'];
    }

    public function execute()
    {
        if ($this->value() === 'Cancel') return $this->previous()->render();
        
        $service = new SubscriptionService($this->request->msisdn);

        try {
       
            $service->subscribe($this->payload('plan'));
            return (new Subscribed($this->request))->render();
            
        } catch (Exception $exception) {
            throw new UssdException($this->request, "Subscription failed. Please try again later");
        }
    }
    
    public function previous(): Screen
    {
        return new Subscribe($this->request);
    }
}
```
