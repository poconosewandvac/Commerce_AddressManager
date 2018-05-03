<?php
/**
 * AddressManager for Modmore's Commerce
 * 
 * Made by Tony Klapatch <tony@klapatch.net>
 * https://github.com/poconosewandvac/Commerce_AddressManager
 */

// Check if user is logged in
$user = $modx->user->get('id');
if (!$user) {
    $modx->sendUnauthorizedPage();
}

// Load AddressManager class
$addressMgr = $modx->getService('addressmanager','AddressManager', $modx->getOption('commerce_addressmanager.core_path', null, $modx->getOption('core_path').'components/commerce_addressmanager/').'model/commerce_addressmanager/', $scriptProperties);
if (!($addressMgr instanceof AddressManager) && !($addressMgr->commerce instanceof Commerce)) return '';
if ($addressMgr->commerce->isDisabled()) {
    return $modx->lexicon('commerce.mode.disabled.message');
}

// Load user's addresses
$addresses = $addressMgr->getAddresses($user);

foreach ($addresses as $a) {
    print_r($a->toArray());
}