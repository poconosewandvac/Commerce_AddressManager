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