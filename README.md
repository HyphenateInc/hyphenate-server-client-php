# PHP Client for Hyphenate Services

The PHP Client for Hyphenate Services is for use in server applications on a proxy server, aka, your developer server. Client library serves as a wrapper layer on top of raw REST APIs that allows you to deploy on your server and make APIs requests. [See Hyphenate APIs](http://docs.hyphenate.io/docs/server-overview).

## Support

This library is open source. We encourage you to contribute to make the code base better! If you encountered any bug or feature suggestion, please [submit an issue](https://github.io.hyphenateInc/hyphenate-server-client-java/issues) or email support@hyphenate.io for urgent fixes.


## Requirement

- Run PHP file on localhost

1. Download Apache XAMPP [download](https://www.apachefriends.org/download.html)
2. Place the PHP project folder in `Applications/XAMPP/xamppfiles/htdocs/`
3. Update `index.php` header from `Applications/XAMPP/xamppfiles/htdocs/` to point to project folder
```php
header('Location: '.$uri.'/hyphenate-server-client-php/');
```

- mkdir(): Permission denied issue. 



### Optional 

- PHPUnit, unit testing for PHP. [Getting Started](https://phpunit.de/getting-started.html)

- PHP version update. 5.6 or above is required. Latest version 7.1. 
```php
$ brew install homebrew/php/php71
```

## Installation



### Configuration 

Update Hyphenate app configurations before use the constructor of the class, Hyphenate.

```php
$options['org_name']='hyphenatedemo';
$options['app_name']='demo';
$options['client_id']='YXA68E7DkM4uEeaPwTPbScypMA';
$options['client_secret']='YXA63_RZdbtXQB9QZsizSCgMC70_4Rs';

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
