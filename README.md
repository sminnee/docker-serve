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

Finally, you can run phpunit tests within a docker container by running the following command:

```
$> docker-phpunit
```

Coming soon
-----------

 * Running arbitrary commands
 * Running a web server
 * Managing support services (such as starting a database)
