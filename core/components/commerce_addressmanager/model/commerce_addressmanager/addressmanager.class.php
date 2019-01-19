<?php

/**
 * AddressManager for Modmore's Commerce
 * 
 * Made by Tony Klapatch <tony@klapatch.net>
 * https://github.com/poconosewandvac/Commerce_AddressManager
 */

class AddressManager {
    public $modx;
    public $user;
    public $commerce;
    public $config = [];
    public $errors = [];
    private $allowedTypes = ["shipping", "billing"];

    /**
     * Initialize modX, Commerce, and user
     *
     * @param modX $modx
     * @param array $config
     */
    public function __construct(modX &$modx, array $config = array()) {
        // Initialize AddressManager
        $this->modx =& $modx;
        $this->user = $config['user'];

        $basePath = $this->modx->getOption('commerce_addressmanager.core_path', $config, $this->modx->getOption('core_path').'components/commerce_addressmanager/');
        $assetsUrl = $this->modx->getOption('commerce_addressmanager.assets_url', $config, $this->modx->getOption('assets_url').'components/commerce_addressmanager/');

        $this->config = array_merge([
            'corePath' => $corePath,
            'modelPath' => $corePath.'model/',
            'processorsPath' => $corePath.'processors/',
            'controllersPath' => $corePath.'controllers/',
            'chunksPath' => $corePath.'elements/chunks/',
            'snippetsPath' => $corePath.'elements/snippets/',
            'baseUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl.'css/',
            'jsUrl' => $assetsUrl.'js/',
            'connectorUrl' => $assetsUrl.'connector.php'
        ]);
        $this->config['requiredFields'] = array_map('trim', explode(',', $config['requiredFields']));

        // Load Commerce
        $commercePath = $this->modx->getOption('commerce.core_path', null, $this->modx->getOption('core_path') . 'components/commerce/') . 'model/commerce/';
        $commerceParams = ['mode' => $this->modx->getOption('commerce.mode')];
        $this->commerce = $this->modx->getService('commerce', 'Commerce', $commercePath, $commerceParams);
    }

    /**
     * Register frontend assets
     *
     * @param boolean $css Registers CSS
     * @param boolean $js Registers JS
     * @return void
     */
    public function registerAssets($css = true, $js = true) {
        if ($css) {
            $this->modx->regClientCSS($this->config['cssUrl'] . 'addressmanager.css');
        }
        if ($js) {
            $this->modx->regClientScript($this->config['jsUrl'] . 'addressmanager.js');
        }
    }

    /**
     * Gets the user id
     * 
     * @return int
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Adds address validation error
     *
     * @param string Field
     * @return void
     */
    public function addAddressError($key) {
        // Get the correct lexicon to identify the field.
        switch ($key) {
            case "fullname":
                $value = "commerce.address.name";
                break;

            case "address1":
                $value = "commerce.address.address";
                break;

            default:
                $value = "commerce.address." . $key;
                break;
        }

        $this->addressErrors[$key] = $value;
    }

    /**
     * Gets all address validation errors
     *
     * @return array
     */
    public function getAddressErrors() {
        return $this->addressErrors;
    }

    /**
     * Gets the user's addresses from comAddress.
     * 
     * @param string $type Type of address, shipping or billing
     * @return comAddress xPDOObjects collection
     */
    public function getAddresses($type) {
        return $this->modx->getCollection('comAddress', [
            'user' => $this->getUser(),
            'type' => $type,
            'remember' => 1
        ]);
    }

    /**
     * Gets a specific user address
     * 
     * @param int $id comAddress id
     * @param int $remember 0|1
     * @return comAddress xPDOObject
     */
    public function getAddress($id, $remember = 1) {
        return $this->modx->getObject('comAddress', [
            'id' => $id,
            'user' => $this->getUser(),
            'remember' => $remember
        ]);
    }

    /**
     * Basic validation to ensure required fields are not blank.
     *
     * @param comAddress $address
     * @return bool
     */
    public function validateAddress($fields) {
        $valid = true;

        foreach ($fields as $key => $value) {
            if (in_array($key, $this->config['requiredFields']) && empty($value)) {
                $this->addAddressError($key);
                $valid = false;
            }
        }

        return $valid;
    }

    /**
     * "Deletes" a user's address. It only sets the remember column to 0 as to keep old orders displaying the same.
     * 
     * @param int $id comAddress id
     * @return void
     */
    public function deleteAddress($id) {
        $address = $this->getAddress($id);

        if ($address) {
            $address->set('remember', 0);
            $address->save();
        }
    }

    /**
     * "Edits" a user's address. Creates new address and duplicates non-edited information from the old address as to keep old orders displaying the same.
     * 
     * @param int $oldAddress comAddress Current address
     * @param array $data array of values (where key matches comAddress column name)
     * @param string $type type of address (shipping|billing)
     * @param order $order order ID to set comOrderAddress to. 
     * @return int|bool comAddress id
     */
    public function editAddress($oldAddress, $data, $type = null, $order = 0) {
        $newAddress = array_merge($oldAddress->toArray(), $data);

        // Check if the address is the same before adding another, no need for duplicates
        if ($oldAddress->toArray() === $newAddress) {
            return $oldAddress->get('id');
        }
            
        if (!$this->validateAddress($newAddress)) {
            return false;
        }

        $oldAddress->set('remember', 0);
        $oldAddress->save();

        unset($newAddress['id']);
        $comAddress = $this->modx->newObject('comAddress');
        $comAddress->fromArray($newAddress);
        $comAddress->save();

        return $comAddress->get('id');
    }

    /**
     * Add a user's address.
     * 
     * @param array $data array of values (where key matches comAddress column name)
     * @param string $type type of address (shipping|billing)
     * @param order $order order ID to set comOrderAddress to. 
     * @return int|bool comAddress id
     */
    public function addAddress($user, $data, $type, $order = 0) {
        $data['remember'] = 1;
        $data['user'] = $user;
        $data['type'] = $type;

        if (!$this->validateAddress($data) || !in_array($type, $this->allowedTypes)) {
            $this->modx->log(1, 'bad');
            return false;
        }

        $query = $this->modx->newObject("comAddress");
        $query->fromArray($data);
        $query->save();

        if (!$query) {
            return false;
        }

        return $query->get('id');
    }
}
