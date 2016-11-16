# Pimcore - Process Manager

[![Software License](https://img.shields.io/badge/license-GPLv3-brightgreen.svg?style=flat)](LICENSE.md)

Process Manager Plugin keeps track of all your "long running jobs". It adds a nive GUI and a new portlet for your Dashboard.

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
if(class_exists('\ProcessManager\Model\Process')) { //Always check if the plugin is installed
    $process = new \ProcessManager\Model\Process();
    $process->setName('Task Name');         //Name of your Task
    $process->setTotal(100);                //Total steps of your Task
    $process->setMessage('Loading');        //Message
    $process->setProgress(0);               //Initial Progress
    $process->save();                       //Save
}
```

### Advance the Progress

```php
if(class_exists('\ProcessManager\Model\Process')) {
    if($process instanceof \ProcessManager\Model\Process) {
        $process->progress();
    }
}
```

### Finish the Progress

```php
if(class_exists('\ProcessManager\Model\Process')) {
    if($process instanceof \ProcessManager\Model\Process) {
        $process->delete();
    }
}
```


## Copyright and license 
Copyright: [lineofcode.at](http://www.lineofcode.at)
For licensing details please visit [LICENSE.md](LICENSE.md)

![Interface](docs/portlet.png)
![Interface](docs/panel.png)