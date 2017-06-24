# Pimcore - Process Manager

[![Software License](https://img.shields.io/badge/license-GPLv3-brightgreen.svg?style=flat)](LICENSE.md)

Process Manager Plugin keeps track of all your "long running jobs". It adds a nive GUI and a new portlet for your Dashboard. You can also create Executables and run them with one click. (It's planned to integrate a CRON like Syntax for recurring tasks)

## Plugins using Process Manager

 - [ImportDefinitions](https://github.com/w-vision/ImportDefinitions)
 - [CoreShop](https://github.com/CoreShop/CoreShop)

## Getting started

* Download Plugin and place it in your plugins directory
* Open Extension Manager in Pimcore and enable/install Plugin
* After Installation within Pimcore Extension Manager, you have to reload Pimcore
* Open Settings -> Process Manager

or install it via composer on an existing pimcore installation

```
composer require dpfaffenbauer/process-manager
```

or for the nightly dev version

```
composer require dpfaffenbauer/process-manager dev-master
```

## Integrate to your Task

### Create new Process

```php

$process = $container->get('process_manager.factory.process')->createNew();
$process->setName('Task Name');         //Name of your Task
$process->setTotal(100);                //Total steps of your Task
$process->setMessage('Loading');        //Message
$process->setProgress(0);               //Initial Progress
$process->save();                       //Save
```

### Advance the Progress

```php
$process->progress();
$process->save();
```

### Finish the Progress

```php
$process->delete();
```


## Copyright and license 
Copyright: [lineofcode.at](http://www.lineofcode.at)
For licensing details please visit [LICENSE.md](LICENSE.md)

![Interface](docs/portlet.png)
![Interface](docs/executables.png)
![Interface](docs/panel.png)