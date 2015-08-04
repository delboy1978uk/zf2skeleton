zf2skeleton
===========
A ZF2 skeleton app with a bit more meat on the bone.
Included Modules
----------------
* User Registration using ZfcUser
* Email using MtMail
* Email Verification using HtUserRegistration
* Authorisation using BjyAuthorise
* Static Pages using PhlySimplePage
* ZendDeveloperTools and BjyProfiler for development
Installation
------------
Install using composer (@todo - register package!)
```
composer create-project delboy1978uk/zf2skeleton yourprojectnamehere v1.0.0
```
Configure
---------
* Create a database and import the data/schema/schema.sql file.
* Copy config/autoload/local.php.dist to config/autoload/local.php and enter your DB details.
* Do the same for bjyprofiler.local.php.dist and zenddevelopertools.local.php.dist, or disable the modules in config/application.config.php

