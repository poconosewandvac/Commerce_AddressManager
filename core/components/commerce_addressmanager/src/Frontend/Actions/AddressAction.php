<?php

namespace PoconoSewVac\AddressManager\Frontend\Actions;

use modmore\Commerce\Adapter\AdapterInterface;
 
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
     * Errors
     */
    protected $errors = [];
    protected $fieldErrors = [];

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
     * Output for the action
     *
     * @return array
     */
    public function output()
    {
        return array_merge($this->result, ['errors' => $this->errors, 'field_errors' => $this->fieldErrors]);
    }

    /**
     * Execute the action
     *
     * @return void
     */
    public abstract function execute();
}