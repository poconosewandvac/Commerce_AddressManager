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
     * @return comAddress xPDOObject
     */
    public function getAddress($id) {
        $query = $this->modx->newQuery('comAddress');
        $query->where([
            'id' => $id,
            'user' => $this->getUser()
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
        $query = $this->modx->newQuery('comAddress');
        $query->where([
            'id' => $id,
            'user' => $this->getUser()
        ]);

        $address = $this->modx->getObject('comAddress', $query);

        if ($address) {
            $address->set('remember', 0);
            $address->save();
        }
    }
}