<?php
/**
 * AddressManager for Modmore's Commerce
 * 
 * Made by Tony Klapatch <tony@klapatch.net>
 * https://github.com/poconosewandvac/Commerce_AddressManager
 */

// Legacy chunk templates
$tpl = $modx->getOption("tpl", $scriptProperties);
$editTpl = $modx->getOption("editTpl", $scriptProperties);
$addTpl = $modx->getOption("addTpl", $scriptProperties);
$errorTpl = $modx->getOption("editTpl", $scriptProperties);
$errorPlaceholder = $modx->getOption("errorPlaceholder", $scriptProperties, "address_error");
$tplWrapper = $modx->getOption("tplWrapper", $scriptProperties);

// Properties
$values = $modx->getOption("values", $scriptProperties, $_REQUEST["values"]);
$requiredFields = $modx->getOption("requiredFields", $scriptProperties, "fullname, email, address1, zip, city, state, country, phone");
$registerCss = (bool)$modx->getOption("registerCss", $scriptProperties, true);
$registerJs = (bool)$modx->getOption("registerJs", $scriptProperties, true);
$pageId = $modx->resource->get('id');

// Check if user is logged in
$user = $modx->user->get('id');
if (!$user) {
    $modx->sendUnauthorizedPage();
}

// Load AddressManager class
$addressMgr = $modx->getService('addressmanager','AddressManager', $modx->getOption('commerce_addressmanager.core_path', null, $modx->getOption('core_path').'components/commerce_addressmanager/').'model/commerce_addressmanager/', [$scriptProperties, 'user' => $user, 'requiredFields' => $requiredFields]);
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
if (isset($_REQUEST["add"]) && isset($_REQUEST["type"]) && is_array($values)) {
    $addressMgr->addAddress($user, $values, $_REQUEST["type"]);
    
    if (!empty($addressMgr->getAddressErrors())) {
        foreach ($addressMgr->getAddressErrors() as $key => $value) {
            $errors .= $modx->getChunk($errorTpl, ['field' => $key, 'lexicon' => $value]);
        }
        $modx->setPlaceholder($errorPlaceholder, $errors);
    } else {
        $modx->sendRedirect($modx->makeUrl($pageId));
    }
}

// Handle editing
if ((int)$_REQUEST["edit"] > 0 && is_array($values)) {
    $edit = $addressMgr->getAddress($_REQUEST["edit"]);
    
    if ($edit) {
        $newAddress = $addressMgr->editAddress($edit, $values);
        
        if (!empty($addressMgr->getAddressErrors())) {
            foreach ($addressMgr->getAddressErrors() as $key) {
                $errors .= $modx->getChunk($errorTpl, ['field' => $key, 'lexicon' => $value]);
            }
            $modx->setPlaceholder($errorPlaceholder, $errors);
        } else {
            $modx->sendRedirect($modx->makeUrl($pageId));
        }
    }
}

// Handle deletes.
if (isset($_REQUEST["delete"]) && (int)$_REQUEST["delete"] > 0) {
    $addressMgr->deleteAddress($_REQUEST["delete"]);
    $modx->sendRedirect($modx->makeUrl($pageId));
}

// Load user's addresses
$shippingAddresses = $addressMgr->getAddresses("shipping");
$billingAddresses = $addressMgr->getAddresses("billing");

// Support legacy installations of Address Manager using MODX chunks for tpls
// This may be removed in the future
if (!empty($tpl)) {
    foreach ($shippingAddresses as $a) {
        $shipping .= $modx->getChunk($tpl, array_merge($a->toArray(), ['editTpl' => $editTpl]));
    }
    foreach ($billingAddresses as $a) {
        $billing .= $modx->getChunk($tpl, array_merge($a->toArray(), ['editTpl' => $editTpl]));
    }

    return $modx->getChunk($tplWrapper, ["shipping" => $shipping, "billing" => $billing, "addTpl" => $addTpl]);
}

$templatePath = $modx->getOption('commerce_addressmanager.core_path', null, $modx->getOption('core_path').'components/commerce_addressmanager/') . 'templates/';
$loader = $addressMgr->commerce->twig->getLoader();
$loader->addLoader(new Twig\Loader\FilesystemLoader($templatePath));

$output = $addressMgr->commerce->twig->render('addressmanager/manager.twig', [
    'shipping_addresses' => $shippingAddresses,
    'billing_addresses' => $billingAddresses,
    'link' => $modx->makeUrl($pageId),
    'errors' => $errors ?? '',
]);
return $addressMgr->commerce->adapter->parseMODXTags($output);