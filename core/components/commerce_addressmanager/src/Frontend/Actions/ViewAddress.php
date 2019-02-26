<?php

namespace PoconoSewVac\AddressManager\Frontend\Actions;

class ViewAddress extends AddressAction
{
    public $template = 'addressmanager/edit.twig';

    public function execute()
    {
        $address = $this->adapter->getObject('comAddress', [
            'user' => $this->getUserId(),
            'remember' => 1,
            'id' => $this->getField('view'),
        ]);

        if (!$address) {
            header("Location: " . $this->adapter->makeResourceUrl($this->adapter->getOption('error_page', null, 1), '', '', 'full'));
            die();
        }

        $this->result['address'] = $address->toArray();
        
        return $this;
    }
}