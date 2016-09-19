Docker Serve
============

Following in the footsteps of [silverstripe/serve](https://github.com/assertchris/silverstripe-serve), this
module adds straightforward docker integration to your SilverStripe project.

Usage
-----

First, add the package ot your project

```
$> composer require silverstripe/docker-serve
```

Next, add an entry to your composer.json saying what docker image to use:

```json
  ...
  "extra": {
   ... 
    "docker-container": "sminnee/silverstripe-lamp:jessie"
   ... 
  },
  ...
```

You can run your tests with docker-phpunit:

```
$> vendor/bin/docker-phpunit
```

And you can run a webserver based on silverstripe/serve with the following command:

```
$> vendor/bin/docker-serve
```

Coming soon
-----------

 * Running arbitrary commands
 * Managing support services (such as starting a database)
