<?php

namespace PoconoSewVac\AddressManager\Frontend\Actions;

use modmore\Commerce\Adapter\AdapterInterface;
use CommerceGuys\Intl\Country\CountryRepository;

abstract class AddressAction
{
    /**
     * @var AdapterInterface
     */
    protected $adapter;
    
    protected $userId = 0;
    protected $fields = [];

    /**
     * Templating
     */
    protected $template = 'addressmanager/list.twig';
    protected $result = [];

    /**
     * Errors/messaging
     */
    protected $errors = [];
    protected $fieldErrors = [];
    protected $messages = [];

    protected $allowedTypes = ['shipping', 'billing'];

    /**
     * AddressAction constructor
     *
     * @param int $userId logged in user's ID
     * @param array $fields fields submitted to server
     */
    public function __construct(AdapterInterface $adapter, int $userId, array $fields)
    {
        $this->adapter = $adapter;
        $this->userId = $userId;
        $this->fields = $fields;
    }

    /**
     * Get the user ID
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Get an individual field from the submitted fields
     *
     * @param string $fieldName
     * @return mixed
     */
    public function getField($fieldName)
    {
        return $this->fields[$fieldName];
    }

    /**
     * Add a field error (individual input)
     *
     * @param string $fieldName
     * @param string $error
     * @return void
     */
    public function addFieldError($fieldName, $error)
    {
        $this->fieldErrors[$fieldName] = $error;
    }

    /**
     * Get all field errors
     *
     * @return array
     */
    public function getFieldErrors()
    {
        return $this->fieldErrors;
    }

    /**
     * Add a generic error
     *
     * @param [type] $error
     * @return void
     */
    public function addError($error)
    {
        $this->errors[] = $error;
    }

    /**
     * Get all generic errors
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Add a message to the output
     *
     * @param string $message
     * @return void
     */
    public function addMessage($message)
    {
        $this->messages[] = $message;
    }

    /**
     * Get all messages outputted
     *
     * @return void
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Get available countries
     *
     * @return array
     * @todo
     */
    /* public function getCountries()
    {
        $commerceMode = $this->adapter->getOption('commerce.mode', null, 'test');
        $countryRepo = new CountryRepository();

        $countryModule = $this->adapter->getObject('comModule', [
            'name' => 'commerce.module.address_validation.country'
        ]);

        $allowedCountries = $commerceModule->getProperty('allowed_countries');
        $disallowedCountries = $commerceModule->getProperty('disallowed_countries');

        if (
            ($commerceMode === 'test' && $countryModule->get('enabled_in_test'))
            || ($commerceMode === 'live' && $countryModule->get('enabled_in_live'))
        ) {
            // @todo
        }

        return $countryRepo->getList($this->adapter->getOption('locale'), 'en_US');
    }*/

    /**
     * Output for the action
     *
     * @return array
     */
    public function output()
    {
        $countryRepo = new CountryRepository();
        return array_merge($this->result, [
            'errors' => $this->getErrors(),
            'field_errors' => $this->getFieldErrors(),
            'messages' => $this->getMessages(),
            'countries' => $countryRepo->getList($this->adapter->getOption('locale'), 'en_US')
        ]);
    }

    /**
     * Execute the action
     *
     * @return void
     */
    public abstract function execute();
}