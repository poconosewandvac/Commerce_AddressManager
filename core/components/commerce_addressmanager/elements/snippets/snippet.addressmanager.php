<?php
/**
 * AddressManager for Modmore's Commerce
 * 
 * Made by Tony Klapatch <tony@klapatch.net>
 * https://github.com/poconosewandvac/Commerce_AddressManager
 */

// Properties
$tpl = $modx->getOption("tpl", $scriptProperties, "AddressManagerRow");
$editTpl = $modx->getOption("editTpl", $scriptProperties, "AddressManagerEdit");
$addTpl = $modx->getOption("addTpl", $scriptProperties, "AddressManagerEdit");
$tplWrapper = $modx->getOption("tplWrapper", $scriptProperties, "AddressManagerWrap");
$values = $modx->getOption("values", $scriptProperties, $_REQUEST["values"]);
$registerCss = (bool)$modx->getOption("registerCss", $scriptProperties, true);
$registerJs = (bool)$modx->getOption("registerJs", $scriptProperties, true);

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

// Load commerce lexicons for fields
$modx->lexicon->load('commerce:default');
$modx->lexicon->load('commerce:frontend');

// Register required assets if not using custom css/js
$addressMgr->registerAssets($registerCss, $registerJs);

// Handle adding
if (isset($_REQUEST["add"]) && isset($_REQUEST["type"])) {
    $addressMgr->addAddress($user, $values, $_REQUEST["type"]);
    $modx->sendRedirect($modx->makeUrl($modx->resource->get('id')));
}

// Handle editing
if (isset($_REQUEST["edit"]) && (int)$_REQUEST["edit"] > 0) {
    $edit = $addressMgr->getAddress($_REQUEST["edit"]);
    
    if ($edit && is_array($values)) {
        $newAddress = $addressMgr->editAddress($edit, $values);
        $modx->sendRedirect($modx->makeUrl($modx->resource->get('id')));
    }
}

// Handle deletes.
if (isset($_REQUEST["delete"]) && (int)$_REQUEST["delete"] > 0) {
    $addressMgr->deleteAddress($_REQUEST["delete"]);
    $modx->sendRedirect($modx->makeUrl($modx->resource->get('id')));
}

// Load user's addresses
$shipping = $addressMgr->getAddresses("shipping");
$billing = $addressMgr->getAddresses("billing");

foreach ($shipping as $a) {
    $shippingAddresses .= $modx->getChunk($tpl, array_merge($a->toArray(), ['editTpl' => $editTpl]));
}
foreach ($billing as $a) {
    $billingAddresses .= $modx->getChunk($tpl, array_merge($a->toArray(), ['editTpl' => $editTpl]));
}

return $modx->getChunk($tplWrapper, ["shipping" => $shippingAddresses, "billing" => $billingAddresses, "addTpl" => $addTpl]);