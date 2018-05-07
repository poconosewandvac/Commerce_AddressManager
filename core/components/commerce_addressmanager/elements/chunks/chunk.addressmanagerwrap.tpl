<div class="address-wrapper">
    <div class="address-manager">
        <div class="address-column shipping-addresses">
            <h4>Shipping Addresses</h4>
            
            <div>
                [[+shipping]]
            
                <div class="address-row address-row-add">
                    <button class="address-open address-add">Add Address</button>
                    <div class="address-content">
                        <div>
                            [[!$[[+addTpl]]? &addressType=`shipping` &methodType=`add`]]
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="address-column billing-addresses">
            <h4>Billing Addresses</h4>
            
            <div>
                [[+billing]]
                
                <div class="address-row address-row-add">
                    <button class="address-open address-add">Add Address</button>
                    <div class="address-content">
                        <div>
                            [[!$[[+addTpl]]? &addressType=`billing` &methodType=`add`]]
                        </div>
                    </div>
                </div>
            
        </div>
    </div>
</div>