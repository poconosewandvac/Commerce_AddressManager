<?php

namespace PoconoSewVac\AddressManager\Frontend\Actions;

class DeleteAddress extends AddressAction
{
    public $template = 'addressmanager/list.twig';

    public function execute()
    {
        $address = $this->adapter->getObject('comAddress', [
            'user' => $this->getUserId(),
            'remember' => 1,
            'id' => $this->getField('delete'),
        ]);

        if (!$address) {
            header("Location: " . $this->adapter->makeResourceUrl($this->adapter->getOption('error_page'), '', '', 'full'));
            die();
        }

        $address->set('remember', 0);
        if ($address->save()) {
            $this->addMessage('Address deleted successfully!');
            header("Location: " . $this->adapter->makeResourceUrl($this->adapter->getOption('commerce_addressmanager.address_page'), '', '', 'full'));
            die();
        }

        return $this;
    }
}