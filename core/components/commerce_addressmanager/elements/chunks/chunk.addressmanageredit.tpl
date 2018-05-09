<form action="[[~[[*id]]]]" method="POST">
    <label for="fullname-[[!+id]]">[[%commerce.address.name]]</label>
    <input id="fullname-[[!+id]]" type="text" name="values[fullname]" value="[[!+fullname]]" required>
        
    <label for="email-[[!+id]]">[[%commerce.address.email]]</label>
    <input id="email-[[!+id]]" type="email" name="values[email]" value="[[!+email]]" required>
        
    <label for="company-[[!+id]]">[[%commerce.address.company]] <small>[[%commerce.address.optional]]</small></label>
    <input id="company-[[!+id]]" type="text" name="values[company]" value="[[!+company]]">

    <label for="address1-[[!+id]]">[[%commerce.address.address]]</label>
    <input id="address1-[[!+id]]" type="text" name="values[address1]" value="[[!+address1]]" required>
        
    <label for="address2-[[!+id]]">[[%commerce.address.address2]] <small>[[%commerce.address.optional]]</small></label>
    <input id="address2-[[!+id]]" type="text" name="values[address2]" value="[[!+address2]]">
        
    <label for="zip-[[!+id]]">[[%commerce.address.zip]]</label>
    <input id="zip-[[!+id]]" type="text" name="values[zip]" value="[[!+zip]]" required>
        
    <label for="city-[[!+id]]">[[%commerce.address.city]]</label>
    <input id="city-[[!+id]]" type="text" name="values[city]" value="[[!+city]]" required>
        
    <label for="state-[[!+id]]">[[%commerce.address.state]]</label>
    <input id="state-[[!+id]]" type="text" name="values[state]" value="[[!+state]]" required>
        
    <label for="country-[[!+id]]">[[%commerce.address.country]]</label>
    <input id="country-[[!+id]]" type="text" name="values[country]" value="[[!+country]]" required>
        
    <label for="phone-[[!+id]]">[[%commerce.address.phone]]</label>
    <input id="phone-[[!+id]]" type="tel" name="values[phone]" value="[[!+phone]]" required>

    <input type="hidden" name="[[!+methodType:default=`edit`]]" value="[[!+id:default=`1`]]">
    <input type="hidden" name="type" value="[[!+addressType]]">
    <button class="address-button-save" type="submit">Save</button>
    [[+methodType:isnot=`add`:then=`<a class="address-button-delete" href="[[~[[*id]]]]?delete=[[!+id]]">Delete</a>`]]
</form>