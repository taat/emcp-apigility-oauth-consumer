emcp-apigility-oauth-consumer
=============================

Module to help you consume 3rd party OAuth protected resources, such as Withings, Twitter, etc..


#Setup

MySQL is required for now, in order to temporarily store `request_tokens`.

These `request_tokens` will be upgraded to `access_tokens` once a User has authorized your
 Apigility app.

##local.php requirements

```php
  'oauth-client'=> array(
        'withings' => array(
            'consumer_key' => 'YOUR_CONSUMER_KEY',
            'consumer_secret' => 'YOUR_CONSUMER_SECRET',
            'request_token_url' => 'https://oauth.withings.com/account/request_token', #GET
            'authorize_url' => 'https://oauth.withings.com/account/authorize',
            'access_token_url' => 'https://oauth.withings.com/account/access_token',
        ),
        'twitter' => array(
            'consumer_key' => 'YOUR_CONSUMER_KEY',
            'consumer_secret' => 'YOUR_CONSUMER_SECRET',
            'request_token_url' => 'http://api.twitter.com/oauth/request_token', #POST
        ),
    ),
```

#OAuth flow

Start by passing a call to the Authorization URL service.
You will be given back a URL that your API Clients will need to click through.

Once they click through, the OAuth Provider should callback your provided `oauth_callback` url.
Giving you a temporary `request_token` , which is stored in MySQL.

That `request_token` needs to be upgraded subsequently to an `access_token` ...
That is done by the `access_token` service and your callback URL.  This basically signals
apgility to say 'this user has approved your app via OAuth'.