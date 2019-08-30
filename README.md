# TNM TruRoute USSD Adapter

This package creates adapters for Laravel to send responses to TruRoute USSD Client and decode its USSD requests using built-in Laravel Illuminate Facades.

## Installation
```
composer require tnmdev/ussd
```


Then run the migrations to create session tracking tables
```
php artisan migrate
```

## Usage
### 1.  Decoding Requests
USSD adapter decodes xml requests to `request` object that extends `\Illuminate\Http\Request`.

To use it pass the TNM USSD Request to your controller methods.

```php
use \TNM\USSD\Http\Request

class UssdController extends Controller
{
    public function __invoke(Request $request)
    {
        $amount = $request->message;
        ...  
    }
}
```

The request class exposes four properties from the xml request passed on by USSDC. 

| Property | Description |
| ---------| ------------- |
| message | The message passed from USSD |
| type | Integer value representing the type of request |
| session | USSD session ID |
| msisdn | The number making the USSD request |

### 2. Encoding Response
USSD adapter extends Laravel's response facade to generate xml response to send to USSDC.

To send USSD response call 
```php
return response()->ussd($responseMessage, Response::RELEASE)
```

The `ussd()` macro takes two parameters. The first one is the message to send to USSD screen and the second is the integer response type. You can use `TNM\USSD\Http\Response`'s constants `RELEASE` and `RESPONSE` to map to their integer equivalents.

## The Screen Object

The USSD screen that is sent to the user is represented by `Screens` which extend the `TNM\USSD\Screen` class. 
### The Mandatory Methods
The `Screen` class will require you to implement the following methods.
* `message()` must return a string message that will be displayed on the screen.
* `options()` must return an array of options which will be exposed to the user. Return an empty array for screens that require no options.
* `execute()` this should be used to implement whatever the app should do with request data. The request data is returned by `getRequestValue()` within the screen object. You may use that to access the request data. If you want to redirect the user to another screen, return the `render()` method of the target screen: `return (new Register($this->request))->render()`. The Screen initialization takes one argument, the `request` object.
### Optional Methods
You can extend the following methods to change some properties of the screen.
* `type()` should return an integer delegated to constants `RELEASE` and `RESPONSE` of the `TNM\USSD\Response` class. It defaults to `RESPONSE` if not overridden.
* `goesBack()` return a boolean value defining if the screen should have a `back` navigation option. You can leave it alone unless you are defining the landing screen.

### The `handle` Static Method
The `handle` method does the magic of orchestrating the navigation of the app. You may call`return Screen::handle($request)` from your invokable USSD Controller.

 ```php
namespace App\Http\Controllers;

use TNM\USSD\Http\Request;
use App\Screens\Welcome;

class UssdController extends Controller 
{
    public function __invoke(Request $request)
    {
        if ($request->isInitial()) return (new Welcome($request))->render();

        return Screen::handle($request);
    }
}
```
The handler will do the heavy lifting. You only need to focus on the three methods of your extending screen.

### Example Screen Implementation

```php
namespace App\Screens;

use TNM\USSD\Screen;
use App\Models\Service;

class Register extends Screen
{
    public function message(): string
    {
        return "Please select a service you want to subscribe to";
    }

    public function options(): array
    {
        return Service::all()->pluck('name');
    }

    public function execute()
    {
        $this->request->trail->addPayload($this->getRequestValue());
        return (new ConfirmSubscription($this->request))->render();
    }
}
```
