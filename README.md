# Pimcore - Process Manager

## Requirements
 - Pimcore 5

[![Software License](https://img.shields.io/badge/license-GPLv3-brightgreen.svg?style=flat)](LICENSE.md)

Process Manager Plugin keeps track of all your "long running jobs". It adds a nive GUI and a new portlet for your Dashboard. You can also create Executables and run them with one click. (It's planned to integrate a CRON like Syntax for recurring tasks)

## Plugins using Process Manager

 - [ImportDefinitions](https://github.com/w-vision/ImportDefinitions)

## Getting started
 * Install via composer ```composer require dpfaffenbauer/process-manager dev-master```
 * Open Extension Manager in Pimcore and enable/install Plugin
 * After Installation within Pimcore Extension Manager, you have to reload Pimcore
 * Open Settings -> Process Manager

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

## Add a new Process Type
 * Add a new Class to your Bundle and implement ``ProcessManagerBundle\Process\ProcessInterface``` Interface
 * Add a new Form Type to your Bundle and add required fields to it
 * Add a new Service with tag ```process_manager.process```
  ```yml
        import_definition.process_manager.process:
            class: Wvision\Bundle\ImportDefinitionsBundle\ProcessManager\ImportDefinitionProcess
            tags:
            - { name: 'process_manager.process', type: 'importdefinition', form-type: 'Wvision\Bundle\ImportDefinitionsBundle\Form\Type\ProcessManager\ImportDefinitionsType' }
  ```
 * Thats it, done. (You still need to handle Process creation within your Bundle yourself, there is no magic behind it)


## Copyright and license 
Copyright: [lineofcode.at](http://www.lineofcode.at)
For licensing details please visit [LICENSE.md](LICENSE.md)

![Interface](docs/portlet.png)
![Interface](docs/executables.png)
![Interface](docs/panel.png)