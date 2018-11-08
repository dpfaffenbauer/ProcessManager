# Pimcore - Process Manager

**Looking for the current stable (version 1)? See https://github.com/dpfaffenbauer/ProcessManager/tree/1.1**

## Requirements
 - Pimcore 5

[![Software License](https://img.shields.io/badge/license-GPLv3-brightgreen.svg?style=flat)](LICENSE.md)

Process Manager Plugin keeps track of all your "long running jobs". It adds a nive GUI and a new portlet for your Dashboard. You can also create Executables and run them with one click. (It's planned to integrate a CRON like Syntax for recurring tasks)

## Plugins using Process Manager

 - [ImportDefinitions](https://github.com/w-vision/ImportDefinitions)

## Getting started
 * Install via composer ```composer require dpfaffenbauer/process-manager:^2.0```
 * Enable via command-line (or inside the pimcore extension manager): ```bin/console pimcore:bundle:enable ProcessManagerBundle```
 * Install via command-line (or inside the pimcore extension manager): ```bin/console pimcore:bundle:install ProcessManagerBundle```
 * Reload Pimcore
 * Open Tools -> Process Manager

## Integrate to your Task

### Create new Process

```php
$processFactory = $container->get('process_manager.factory.process');
$process = $processFactory->createProcess(
    sprintf(
        'Process (%s): %s',
        $date->formatLocalized('%A %d %B %Y'),
        'Special Long Running Task'
    ),                                                  //Name
    'special_task',                                     //Type
    'Message',                                          //Message Text
    100,                                                //Total Steps
    0                                                   //Current Step
);
$process->save();                                       //Save
```

### Advance the Progress

```php
$process->progress();
$process->save();
```

### Finish the Progress

```php
$process->setProgress($process->getTotal());
$process->save();
```

## Using the Process Logger
Process Manager also provides you with the ability to Log what exactly happens in your progress.

```php
$logger = $container->get('process_manager.logger');

//Logs a emergency message
$logger->emergency($process, 'Total of 100 entries found');

//Logs a alert message
$logger->alert($process, 'Total of 100 entries found');

//Logs a critical message
$logger->critical($process, 'Total of 100 entries found');

//Logs a error message
$logger->error($process, 'Total of 100 entries found');

//Logs a warning message
$logger->warning($process, 'Total of 100 entries found');

//Logs a notice message
$logger->notice($process, 'Total of 100 entries found');

//Logs a info message
$logger->info($process, 'Total of 100 entries found');

//Logs a debug message
$logger->debug($process, 'Total of 100 entries found');
```

## Reports
You can also further process the log to create a pretty report. To do that, you have to create
a new service and implement the interface `ProcessManagerBundle\Report\ReportInterface`.
Import Definitions has an example implementation of that [Import Definition Report](https://github.com/w-vision/ImportDefinitions/blob/master/src/ImportDefinitionsBundle/ProcessManager/ImportDefinitionsReport.php)



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
