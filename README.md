# Commerce_AddressManager

Let registered customers view and edit their saved addresses. Requires Modmore's Commerce to use https://www.modmore.com/commerce/

## Basic Usage

Create a new resource and insert the [[!AddressManager]] snippet.

## Required Fields

The default settings are to require all fields that Commerce requires by default (fullname, email, address1, zip, city, state, country, phone). If you need to change this, you can use the requiredFields snippet property with a comma seperated list of fields. You will also have to create a new edit/add chunk to use to change the `required` HTML attributes on the input fields.

## Templates

### Defaults

This extra comes included with a minmalistic frontend CSS and JS that are automatically included on the page when the snippet is loaded. They have been designed to be as unobtrusive to your site as possible, using only vanilla JS and CSS targeting only within the address form. However, if you want to customize or not use them, you can turn them with the registerCss and registerJs snippet properties (set to 0).

### Chunks

There are 4 customizable chunks you can override based on your needs.

- tpl - This is the row of the item in the column. In the default chunk, it displays the wrapper around the editTpl.
- tplWrapper - Surrounds everything, used for positioning the other chunks.
- editTpl - All the input fields, delete button, and save button.
- errorTpl - Used for address verification errors. Placeholders field (column name in comAddress) and lexicon (lexicon name) are available in this chunk. You can control where this is placed in the tplWrapper chunk.
- addTpl - By default, this uses the same chunk as editTpl, however you can customize this separately if needed.