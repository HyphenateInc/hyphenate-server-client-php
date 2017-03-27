# PHP Client for Hyphenate Services

The PHP Client for Hyphenate Services is for use in server applications on a proxy server, aka, your developer server. Client library serves as a wrapper layer on top of raw REST APIs that allows you to deploy on your server and make APIs requests. [See Hyphenate APIs](http://docs.hyphenate.io/docs/server-overview).

## Support

This library is open source. We encourage you to contribute to make the code base better! If you encountered any bug or feature suggestion, please [submit an issue](https://github.io.hyphenateInc/hyphenate-server-client-java/issues) or email support@hyphenate.io for urgent fixes.


## Requirement


## Installation


### Configuration 

Update Hyphenate app configurations before use the constructor, Hyphenate class.

```php
$options['client_id']='hyphenatedemo';
$options['client_secret']='demo';
$options['org_name']='YXA68E7DkM4uEeaPwTPbScypMA';
$options['app_name']='YXA63_RZdbtXQB9QZsizSCgMC70_4Rs';

$h=new Hyphenate($options);
```

## Dependencies  

### Generate keystore

[Keystore Generation with public site certificate](https://docs.hyphenate.io/docs/keystore-generation-with-public-cer)

## REST APIs Documentation 

[Usage](https://api-docs.hyphenate.io)

### Rate Limiting

By default, requests are sent at the expected rate limits for each web service, typically 30 queries per second for free users.  
Each IP address is limited to 30 requests per second. Requests beyond this limit will receive a 429 or 503 error. If you received this error, please reduce the request frequency and try again.
Please contact Hyphenate info@hyphenate.io if you need higher request rate.

## Install PHP IDE

You can use IntelliJ IDEA, or any PHP IDE you prefer to the run the project.
