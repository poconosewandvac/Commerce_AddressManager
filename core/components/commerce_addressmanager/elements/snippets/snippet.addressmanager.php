<?php

/**
 * AddressManager for Modmore's Commerce
 * 
 * Made by Tony Klapatch <tony@klapatch.net>
 * https://github.com/poconosewandvac/Commerce_AddressManager
 */

// Properties
$registerCss = (bool)$modx->getOption ("registerCss", $scriptProperties, true);
$registerJs = (bool)$modx->getOption("registerJs", $scriptProperties, true);
$pageId = $modx->resource->get('id');

// Check if user is logged in
$user = $modx->user->get('id');
if (!$user) {
    $modx->sendUnauthorizedPage();
}

// Set common params for output
$outputParams = [
    'user' => $user,
    'link' => $modx->makeUrl($pageId),
];

// Load AddressManager class
$addressMgr = $modx->getService('addressmanager', 'AddressManager', $modx->getOption('commerce_addressmanager.core_path', null, $modx->getOption('core_path') . 'components/commerce_addressmanager/') . 'model/commerce_addressmanager/', [$scriptProperties, 'user' => $user]);
if (!($addressMgr instanceof AddressManager) && !($addressMgr->commerce instanceof Commerce)) return '';
if ($addressMgr->commerce->isDisabled()) {
    return $modx->lexicon('commerce.mode.disabled.message');
}

// Load commerce lexicons for fields
$modx->lexicon->load('commerce:default');
$modx->lexicon->load('commerce:frontend');
$modx->lexicon->load('commerce:modules');

// Register required assets if not using custom css/js
$addressMgr->registerAssets($registerCss, $registerJs);

$outputData = $addressMgr->getAction()
    ->execute()
    ->output();

$templatePath = $modx->getOption('commerce_addressmanager.core_path', null, $modx->getOption('core_path') . 'components/commerce_addressmanager/') . 'templates/';
$loader = $addressMgr->commerce->twig->getLoader();
$loader->addLoader(new Twig\Loader\FilesystemLoader($templatePath));

$output = $addressMgr->commerce->twig->render('addressmanager/list.twig', array_merge($outputData, $outputParams));
return $addressMgr->commerce->adapter->parseMODXTags($output);