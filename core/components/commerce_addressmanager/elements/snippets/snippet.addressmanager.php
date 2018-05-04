<?php
/**
 * AddressManager for Modmore's Commerce
 * 
 * Made by Tony Klapatch <tony@klapatch.net>
 * https://github.com/poconosewandvac/Commerce_AddressManager
 */

// Properties
$tpl = $modx->getOption("tpl", $scriptProperties, "AddressManagerRow");
$tplWrapper = $modx->getOption("tplWrapper", $scriptProperties, "AddressManagerWrap");

// Check if user is logged in
$user = $modx->user->get('id');
if (!$user) {
    $modx->sendUnauthorizedPage();
}

// Load AddressManager class
$addressMgr = $modx->getService('addressmanager','AddressManager', $modx->getOption('commerce_addressmanager.core_path', null, $modx->getOption('core_path').'components/commerce_addressmanager/').'model/commerce_addressmanager/', [$scriptProperties, 'user' => $user]);
if (!($addressMgr instanceof AddressManager) && !($addressMgr->commerce instanceof Commerce)) return '';
if ($addressMgr->commerce->isDisabled()) {
    return $modx->lexicon('commerce.mode.disabled.message');
}

if (isset($_REQUEST["delete"])) {
    $addressMgr->deleteAddress($_REQUEST["delete"]);
}

// Load user's addresses
$shipping = $addressMgr->getAddresses("shipping");
foreach ($shipping as $a) {
    $shippingAddresses .= $modx->getChunk($tpl, $a->toArray());
}

$billing = $addressMgr->getAddresses("billing");
foreach ($billing as $a) {
    $billingAddresses .= $modx->getChunk($tpl, $a->toArray());
}

return $modx->getChunk($tplWrapper, ["shipping" => $shippingAddresses, "billing" => $billingAddresses]);