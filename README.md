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

```$xslt
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

The request class exposes four public properties from the xml request passed on by USSDC. 

| Property | Description |
| ---------| ------------- |
| Message | The message passed from USSD |
| Type | Integer value representing the type of request |
| session | USSD session ID |
| msisdn | The number making the USSD request |

### 2. Encoding Response
USSD adapter extends Laravel's response facade to generate xml response to send to USSDC.

To send USSD response call 
```
return response()->ussd($responseMessage, Response::RELEASE)
```

The `ussd()` macro takes two parameters. The first one is the message to send to USSD screen and the second is the integer response type. You can use `TNM\USSD\Http\Response`'s constants `RELEASE` and `RESPONSE` to map to their integer equivalents.


