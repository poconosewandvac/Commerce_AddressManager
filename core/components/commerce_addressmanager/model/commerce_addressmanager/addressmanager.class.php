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

    /**
     * Gets the user id
     * 
     * @return int
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Runs validation of address
     * 
     * @param string $type type of address shipping|billing 
     * @param comAddress $address comAddress instance
     * @return bool
     */
    public function validate($type, \comAddress $address)
    {
        /** @var AddressValidation $event */
        $event = $this->commerce->dispatcher->dispatch(\Commerce::EVENT_ADDRESS_VALIDATE, new AddressValidation($address, $type, $this->order));

        if (!$event->hasAnyErrors()) {
            return true;
        }

        /*if ($event->hasMessages()) {
            $messages = $event->getMessages();
            foreach ($messages as $message) {
                $this->response->addError($message);
            }
        }

        if ($event->hasFieldErrors()) {
            $errors = $event->getFieldErrors();
            foreach ($errors as $error) {
                $this->response->addError($error->getMessage(), 400, $error->getField());
                $this->setPlaceholder('error_' . $type . '_' . $error->getField(), $error->getMessage());
            }
        }*/

        return false;
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
        $address = $this->getAddress($id);

        if ($address) {
            $address->set('remember', 0);
            $address->save();
        }
    }

    /**
     * "Edits" a user's address. Creates new address and duplicates non-edited information from the old address as to keep old orders displaying the same.
     * 
     * @param int $id comAddress Current address id
     * @return int comAddress id
     */
    public function editAddress($id, $data) {
        $address = $this->getAddress($id);
        $newAddress = array_merge($address->toArray(), $data);   

        echo '<pre>';
        print_r($data);
        echo '</pre><pre>';
        print_r($newAddress);
        echo '</pre>';

        // Check if the address is the same before adding another
        /* if ($address->toArray() === $newAddress) {
            return $newAddress['id'];
        } else {
            $address->set('remember', 0);
            $address->save();

            $query = $this->modx->newObject('comAddress');
            unset($newAddress['id']);
            $query->fromArray($newAddress);
            $query->save();
        } */
    }
}