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
     * Gets the user's addresses from comAddress.
     * 
     * @param string $type Type of address, shipping or billing
     * @return comAddress xPDOObjects collection
     */
    public function getAddresses($type) {
        $query = $this->modx->newQuery('comAddress');
        $query->select('comOrderAddress.type');
        $query->select($this->modx->getSelectColumns('comAddress', 'comAddress'));
        $query->innerJoin('comOrderAddress','comOrderAddress', ["comAddress.id = comOrderAddress.address"]);
        $query->where([
            'comOrderAddress.type:=' => $type,
            'comAddress.user:=' => $this->getUser(),
            'comAddress.remember:=' => 1
        ]);

        return $this->modx->getCollection('comAddress', $query);
    }

    /**
     * Gets a specific user address
     * 
     * @param int $id comAddress id
     * @param int $remember 0|1
     * @return comAddress xPDOObject
     */
    public function getAddress($id, $remember = 1) {
        $query = $this->modx->newQuery('comAddress');
        $query->where([
            'id' => $id,
            'user' => $this->getUser(),
            'remember' => $remember
        ]);

        return $this->modx->getObject('comAddress', $query);
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
        } else {
            // Get address type if not statically set. @TODO make this into function
            if (!$type) {
                $comAddressType = $this->modx->newQuery('comOrderAddress');
                $comAddressType->where([
                    'address' => $oldAddress->get('id')
                ]);
                $type = $this->modx->getObject('comOrderAddress', $comAddressType)->get('type');
            }

            $oldAddress->set('remember', 0);
            $oldAddress->save();

            unset($newAddress['id']);
            $comAddress = $this->modx->newObject('comAddress');
            $comAddress->fromArray($newAddress);
            $comAddress->save();

            $this->attachOrderAddress($comAddress, $type, $order);
            return $comAddress->get('id');
        }
    }

    /**
     * Add an empty order address to an address 
     * 
     * @param comAddress $address comAddress instance
     * @param string $type type of address (shipping|billing)
     * @param order $order order ID to set comOrderAddress to. 
     * @return int|bool comAddress id
     */
    public function attachOrderAddress($address, $type, $order) {
        $query = $this->modx->newObject('comOrderAddress');
        $query->fromArray([
            'order' => $order,
            'type' => $type,
            'address' => $address->get('id')
        ]);
        $query->save();
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

        $query = $this->modx->newObject("comAddress");
        $query->fromArray($data);
        $query->save();

        if (!$query) {
            return false;
        }

        $this->attachOrderAddress($query, $type, $order);

        return $query->get('id');
    }
}