<?php

if ($object->xpdo) {
    $modx =& $object->xpdo;
    $modelPath = $modx->getOption('commerce_addressmanager.core_path',null,$modx->getOption('core_path').'components/commerce_addressmanager/').'model/';
    $modx->addPackage('commerce_addressmanager',$modelPath);
}
return true;