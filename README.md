# w3nonce
composer package, which serves the functionality working with WordPress Nonces. That means to have especially wp_nonce_*() function implemented in an object orientated way.

## Installation
```shell
	composer require bdow3nuts/w3nonce
```

## How to use

Initialization.

The class Environment delivers the data which are normally delivered by WordPress. In this example the class deliveres the data directly. In practical use with WordPress we have to change it to read the data from WordPress.
```php
	$w3nenvironment = new W3nonceenvironment();
	$w3nonce_object = new W3nonce();
	$w3nonce_object->set_w3nenvironment( $w3environment );
```
Set an new user ID (example '456')
```php
	$w3nenvironment->set_userid( '456' );
```	

Create nonce for an action (example 'firstaction')
```php
	$nonce = $w3nonce_object->wp_nonce_create( 'firstaction' );
```

Verify nonce (example for a nonce created with action 'action-1')
```php
	$verify_true = $w3nonce_object->w3_verify_nonce( $nonce, 'firstaction' );
```

## Current Status
The described functions are working and tested.