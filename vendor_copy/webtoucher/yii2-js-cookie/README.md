# yii2-js-cookie
This extension provides an assets bundle with [JavaScript Cookie](https://github.com/js-cookie/js-cookie/)
for [Yii framework 2.0](http://www.yiiframework.com/) applications.

[![Latest Stable Version](https://poser.pugx.org/webtoucher/yii2-js-cookie/v/stable)](https://packagist.org/packages/webtoucher/yii2-js-cookie)
[![Total Downloads](https://poser.pugx.org/webtoucher/yii2-js-cookie/downloads)](https://packagist.org/packages/webtoucher/yii2-js-cookie)
[![Daily Downloads](https://poser.pugx.org/webtoucher/yii2-js-cookie/d/daily)](https://packagist.org/packages/webtoucher/yii2-js-cookie)
[![Latest Unstable Version](https://poser.pugx.org/webtoucher/yii2-js-cookie/v/unstable)](https://packagist.org/packages/webtoucher/yii2-js-cookie) 
[![License](https://poser.pugx.org/webtoucher/yii2-js-cookie/license)](https://packagist.org/packages/webtoucher/yii2-js-cookie)

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
$ php composer.phar require webtoucher/yii2-js-cookie "*"
```

or add

```
"webtoucher/yii2-js-cookie": "*"
```

to the ```require``` section of your `composer.json` file.

## Usage

In view

```php
webtoucher\cookie\AssetBundle::register($this);
```

or as dependency in your main application asset bundle

```php
class AppAsset extends AssetBundle
{
	// ...

	public $depends = [
		// ...
		'\webtoucher\cookie\AssetBundle'
	];
}

```

Then you can use Cookies in your scripts. Please read [project documentation](https://github.com/js-cookie/js-cookie#basic-usage) for more information.
