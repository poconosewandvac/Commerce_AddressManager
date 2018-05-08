# Commerce_AddressManager

Let registered customers view and edit their saved addresses. Requires Modmore's Commerce to use https://www.modmore.com/commerce/

## Templates

### Defaults

This extra comes included with a minmalistic frontend CSS and JS that are automatically included on the page when the snippet is loaded. They have been designed to be as unobtrusive to your site as possible, using only vanilla JS and CSS targeting only within the address form. However, if you want to customize or not use them, you can turn them with the registerCss and registerJs snippet properties (set to 0).

### Chunks

There are 4 customizable chunks you can override based on your needs.

- tpl - This is the row of the item in the column. In the default chunk, it displays the wrapper around the editTpl.
- tplWrapper - Surrounds everything, used for positioning the other chunks.
- editTpl - All the input fields, delete button, and save button.
- addTpl - By default, this uses the same chunk as editTpl, however you can customize this separately if needed.