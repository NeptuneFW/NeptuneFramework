<?php

// autoload_classmap.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'Applications\\Production\\Kernel\\HttpKernel' => $baseDir . '/applications/production/kernel/httpkernel.php',
    'Applications\\Production\\Request\\Controller\\HomeController' => $baseDir . '/applications/production/request/controller/HomeController.php',
    'EasyPeasyICS' => $vendorDir . '/phpmailer/phpmailer/extras/EasyPeasyICS.php',
    'Libs\\Application\\Application' => $baseDir . '/libs/application/application.php',
    'Libs\\Arrays\\Arrays' => $baseDir . '/libs/arrays/arrays.php',
    'Libs\\Assets\\Assets' => $baseDir . '/libs/assets/assets.php',
    'Libs\\Router\\AbstractResponse' => $baseDir . '/libs/router/abstractresponse.php',
    'Libs\\Router\\AbstractRouteFactory' => $baseDir . '/libs/router/abstractroutefactory.php',
    'Libs\\Router\\App' => $baseDir . '/libs/router/app.php',
    'Libs\\Router\\DataCollection\\DataCollection' => $baseDir . '/libs/router/dataCollection/datacollection.php',
    'Libs\\Router\\DataCollection\\HeaderDataCollection' => $baseDir . '/libs/router/dataCollection/headerdatacollection.php',
    'Libs\\Router\\DataCollection\\ResponseCookieDataCollection' => $baseDir . '/libs/router/dataCollection/responsecookiecollection.php',
    'Libs\\Router\\DataCollection\\RouteCollection' => $baseDir . '/libs/router/dataCollection/routecollection.php',
    'Libs\\Router\\DataCollection\\ServerDataCollection' => $baseDir . '/libs/router/dataCollection/serverdatacollection.php',
    'Libs\\Router\\Exceptions\\DispatchHaltedException' => $baseDir . '/libs/router/exceptions/DispatchHaltedException.php',
    'Libs\\Router\\Exceptions\\DuplicateServiceException' => $baseDir . '/libs/router/exceptions/DuplicateServiceException.php',
    'Libs\\Router\\Exceptions\\HttpException' => $baseDir . '/libs/router/exceptions/HttpException.php',
    'Libs\\Router\\Exceptions\\HttpExceptionInterface' => $baseDir . '/libs/router/exceptions/HttpExceptionInterface.php',
    'Libs\\Router\\Exceptions\\KleinExceptionInterface' => $baseDir . '/libs/router/exceptions/KleinExceptionInterface.php',
    'Libs\\Router\\Exceptions\\LockedResponseException' => $baseDir . '/libs/router/exceptions/LockedResponseException.php',
    'Libs\\Router\\Exceptions\\RegularExpressionCompilationException' => $baseDir . '/libs/router/exceptions/RegularExpressionCompilationException.php',
    'Libs\\Router\\Exceptions\\ResponseAlreadySentException' => $baseDir . '/libs/router/exceptions/ResponseAlreadySentException.php',
    'Libs\\Router\\Exceptions\\RoutePathCompilationException' => $baseDir . '/libs/router/exceptions/RoutePathCompilationException.php',
    'Libs\\Router\\Exceptions\\UnhandledException' => $baseDir . '/libs/router/exceptions/UnhandledException.php',
    'Libs\\Router\\Exceptions\\UnknownServiceException' => $baseDir . '/libs/router/exceptions/UnknownServiceException.php',
    'Libs\\Router\\HttpStatus' => $baseDir . '/libs/router/httpstatus.php',
    'Libs\\Router\\Request' => $baseDir . '/libs/router/request.php',
    'Libs\\Router\\Response' => $baseDir . '/libs/router/response.php',
    'Libs\\Router\\ResponseCookie' => $baseDir . '/libs/router/responsecookie.php',
    'Libs\\Router\\Route' => $baseDir . '/libs/router/route.php',
    'Libs\\Router\\RouteFactory' => $baseDir . '/libs/router/routefactory.php',
    'Libs\\Router\\Router' => $baseDir . '/libs/router/router.php',
    'Libs\\Router\\ServiceProvider' => $baseDir . '/libs/router/serviceprovider.php',
    'Libs\\Url\\Url' => $baseDir . '/libs/url/url.php',
    'Libs\\Validator\\Attribute' => $baseDir . '/libs/validator/attribute.php',
    'Libs\\Validator\\ErrorBag' => $baseDir . '/libs/validator/errorbag.php',
    'Libs\\Validator\\Helper' => $baseDir . '/libs/validator/helper.php',
    'Libs\\Validator\\MimeTypeGuesser' => $baseDir . '/libs/validator/mimetypeguesser.php',
    'Libs\\Validator\\MissingRequiredParameterException' => $baseDir . '/libs/validator/missingrequiredparameterexception.php',
    'Libs\\Validator\\Rule' => $baseDir . '/libs/validator/rule.php',
    'Libs\\Validator\\RuleNotFoundException' => $baseDir . '/libs/validator/rulenotfoundexception.php',
    'Libs\\Validator\\RuleQuashException' => $baseDir . '/libs/validator/rulequashexception.php',
    'Libs\\Validator\\Rules\\Accepted' => $baseDir . '/libs/validator/Rules/accepted.php',
    'Libs\\Validator\\Rules\\After' => $baseDir . '/libs/validator/Rules/after.php',
    'Libs\\Validator\\Rules\\Alpha' => $baseDir . '/libs/validator/Rules/alpha.php',
    'Libs\\Validator\\Rules\\AlphaDash' => $baseDir . '/libs/validator/Rules/alphadash.php',
    'Libs\\Validator\\Rules\\AlphaNum' => $baseDir . '/libs/validator/Rules/alphanum.php',
    'Libs\\Validator\\Rules\\Before' => $baseDir . '/libs/validator/Rules/before.php',
    'Libs\\Validator\\Rules\\Between' => $baseDir . '/libs/validator/Rules/between.php',
    'Libs\\Validator\\Rules\\Date' => $baseDir . '/libs/validator/Rules/date.php',
    'Libs\\Validator\\Rules\\DateUtils' => $baseDir . '/libs/validator/Rules/dateutils.php',
    'Libs\\Validator\\Rules\\Different' => $baseDir . '/libs/validator/Rules/different.php',
    'Libs\\Validator\\Rules\\Email' => $baseDir . '/libs/validator/Rules/email.php',
    'Libs\\Validator\\Rules\\FileTrait' => $baseDir . '/libs/validator/Rules/filetrait.php',
    'Libs\\Validator\\Rules\\In' => $baseDir . '/libs/validator/Rules/in.php',
    'Libs\\Validator\\Rules\\Ip' => $baseDir . '/libs/validator/Rules/ip.php',
    'Libs\\Validator\\Rules\\Ipv4' => $baseDir . '/libs/validator/Rules/ipv4.php',
    'Libs\\Validator\\Rules\\Ipv6' => $baseDir . '/libs/validator/Rules/ipv6.php',
    'Libs\\Validator\\Rules\\Max' => $baseDir . '/libs/validator/Rules/max.php',
    'Libs\\Validator\\Rules\\Min' => $baseDir . '/libs/validator/Rules/min.php',
    'Libs\\Validator\\Rules\\NotIn' => $baseDir . '/libs/validator/Rules/notin.php',
    'Libs\\Validator\\Rules\\Numeric' => $baseDir . '/libs/validator/Rules/numeric.php',
    'Libs\\Validator\\Rules\\Present' => $baseDir . '/libs/validator/Rules/present.php',
    'Libs\\Validator\\Rules\\Regex' => $baseDir . '/libs/validator/Rules/regex.php',
    'Libs\\Validator\\Rules\\Required' => $baseDir . '/libs/validator/Rules/required.php',
    'Libs\\Validator\\Rules\\RequiredIf' => $baseDir . '/libs/validator/Rules/requiredif.php',
    'Libs\\Validator\\Rules\\RequiredUnless' => $baseDir . '/libs/validator/Rules/requiredunless.php',
    'Libs\\Validator\\Rules\\RequiredWith' => $baseDir . '/libs/validator/Rules/requiredwith.php',
    'Libs\\Validator\\Rules\\RequiredWithAll' => $baseDir . '/libs/validator/Rules/requiredwithall.php',
    'Libs\\Validator\\Rules\\RequiredWithout' => $baseDir . '/libs/validator/Rules/requiredwithout.php',
    'Libs\\Validator\\Rules\\RequiredWithoutAll' => $baseDir . '/libs/validator/Rules/requiredwithoutall.php',
    'Libs\\Validator\\Rules\\Same' => $baseDir . '/libs/validator/Rules/same.php',
    'Libs\\Validator\\Rules\\TypeArray' => $baseDir . '/libs/validator/Rules/typearray.php',
    'Libs\\Validator\\Rules\\UploadFile' => $baseDir . '/libs/validator/Rules/uploadfile.php',
    'Libs\\Validator\\Rules\\Url' => $baseDir . '/libs/validator/Rules/url.php',
    'Libs\\Validator\\Validation' => $baseDir . '/libs/validator/validation.php',
    'Libs\\Validator\\Validator' => $baseDir . '/libs/validator/validator.php',
    'PHPMailer' => $vendorDir . '/phpmailer/phpmailer/class.phpmailer.php',
    'PHPMailerOAuth' => $vendorDir . '/phpmailer/phpmailer/class.phpmaileroauth.php',
    'PHPMailerOAuthGoogle' => $vendorDir . '/phpmailer/phpmailer/class.phpmaileroauthgoogle.php',
    'POP3' => $vendorDir . '/phpmailer/phpmailer/class.pop3.php',
    'SMTP' => $vendorDir . '/phpmailer/phpmailer/class.smtp.php',
    'System\\Core\\Kernel' => $baseDir . '/system/core/kernel.php',
    'ntlm_sasl_client_class' => $vendorDir . '/phpmailer/phpmailer/extras/ntlm_sasl_client.php',
    'phpmailerException' => $vendorDir . '/phpmailer/phpmailer/class.phpmailer.php',
);
