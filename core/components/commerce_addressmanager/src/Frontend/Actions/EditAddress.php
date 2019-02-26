<?php

namespace PoconoSewVac\AddressManager\Frontend\Actions;

class EditAddress extends AddressAction
{
    public $template = 'addressmanager/edit.twig';

    /**
     * Map inputted fields to their database columns
     *
     * @return void
     */
    protected function fieldMap()
    {
        return [
            'type' => $this->getField('type'),
            'fullname' => $this->getField('fullname'),
            'firstname' => $this->getField('firstname'),
            'lastname' => $this->getField('lastname'),
            'company' => $this->getField('company'),
            'address1' => $this->getField('address1'),
            'address2' => $this->getField('address2'),
            'address3' => $this->getField('address3'),
            'zip' => $this->getField('zip'),
            'city' => $this->getField('city'),
            'state' => $this->getField('state'),
            'country' => $this->getField('country'),
            'phone' => $this->getField('phone'),
            'mobile' => $this->getField('mobile'),
            'email' => $this->getField('email')
        ];
    }

    public function execute()
    {
        $address = $this->adapter->getObject('comAddress', [
            'user' => $this->getUserId(),
            'remember' => 1,
            'id' => $this->getField('edit'),
        ]);

        if (!$address) {
            return $this;
        }

        $address->fromArray($this->fieldMap());

        if ($address->save()) {
            $this->addMessage('Address saved successfully!');
        }

        $this->result['address'] = $address->toArray();

        return $this;
    }
}