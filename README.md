# Commerce_AddressManager

## In development

Let registered customers view and edit their saved comAddress addresses.

## Templates

...

### Included

...

### Basic Template (no registered assets)

Sample AddressManagerEdit:

```HTML
<h4>Edit Address</h4>
<form action="[[~[[*id]]]]?edit=[[+id]]" method="POST">
    <input type="text" name="values[fullname]" value="[[+fullname]]">
    <input type="text" name="values[address1]" value="[[+address1]]">
    <input type="text" name="values[city]" value="[[+city]]">
    <input type="text" name="values[state]" value="[[+state]]">
    <input type="text" name="values[country]" value="[[+country]]">
    <input type="text" name="values[zip]" value="[[+zip]]">
    <input type="tel" name="values[phone]" value="[[+phone]]">
    <input type="email" name="values[email]" value="[[+email]]">
    <button type="submit">Save</button>
</form>
```

Sample AddressManagerRow:

```HTML
<div>
    <p>[[+fullname]]</p>
    <p>[[+address1]]</p>
    <p>[[+city]], [[+state]] [[+country]] [[+zip]]</p>
    <p>[[+phone]] - [[+email]]</p>
    <a href="[[~[[*id]]]]?edit=[[+id]]">Edit</a></a>
    <a href="[[~[[*id]]]]?delete=[[+id]]">Delete</a>
    <hr>
</div>
```

Sample AddressManagerWrap:

```HTML
<h4>Shipping Addresses</h4>
[[+shipping]]
<h4>Billing Addresses</h4>
[[+billing]]
```