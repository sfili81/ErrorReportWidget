# Javascript error widget report
This widget detects javascript errors in the browser console and sends them via email

The preferred way to install this extension is through [composer](https://getcomposer.org/download/).
Either run
```
composer require sfili81/error-report-widget
```
or add
```
"sfili81/error-report-widget": "*"
```
to your `composer.json` 

## Usage
Once the extension is installed, simply use it in your code by:

```
use sfili81\ErrorReportWidget\ErrorReportWidget;

```
and then insert the code before all other javascript files

```php
<?= ErrorReportWidget::widget() ?>

```

### Email Address Setting  

To set an email address to send the error report to, set a pair like this in the params.php file:
```php
'supportEmail' => 'yourEmailAddress@mail.com'

```