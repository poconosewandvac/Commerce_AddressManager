<?php

namespace PoconoSewVac\AddressManager\Frontend\Actions;

class ViewAddresses extends AddressAction
{
    public function execute()
    {
        $query = $this->adapter->newQuery('comAddress');
        $query->where([
            'user' => $this->getUserId(),
            'remember' => 1,
        ]);

        if ($this->getField('type')) {
            $query->where([
                'type' => $this->getField('type')
            ]);
        }

        $addresses = $this->adapter->getCollection('comAddress', $query);

        foreach ((array) $addresses as $address) {
            $addr = $address->toArray();
            $this->result['addresses'][] = $addr;
            
            if ($address->get('type') === 'shipping') {
                $this->result['shipping_addresses'][] = $addr;
            } else if ($address->get('type') === 'billing') {
                $this->result['billing_addresses'][] = $addr;
            }
        }

        return $this;
    }
}