# Templating (KMS)

KMS allows you to control all aspects of your website from its robust templating system. To begin, choose your site
template. This will be used as the base template for all text/html content on your website. Next, you can add in site
variables, snippets or chunks to enhance the functionality of your template!

## Chunks

Chunks are static blocks of HTML code. This allows you to add alternative static content to areas of your site. Each
chunk has a unique `code id` associated with it which is used to call upon the chunk. The shortcode for adding a chunk
with the code id of `test` would be:

	[[c*test]]

## Snippets

Snippets are executable blocks of PHP. This can add some more advanced functionality to your site. Each snippets has
a unique `code id` associated with it which is used to call upon the snippet. The shortcode for adding a snippet with
the code id of `test` would be:

	[[s*test]]

## Site Variables

Site variables are facilitators for storing data for the templating engine. Each variable has a unique `variable name`
associated with it which is used to call upon the variable. The shortcode for adding a variable with the variable name
of `test` would be:

	[[v*test]]

## Lists

Lists are site specific database tables that can be created for custom data needed for the website. Each
list has a unique `list name` associated with it which is used to call upon the list. The shortcode for adding a list
with the list name of `test` would be:

	[[list*{name of list} per_page=20, limit=-1, sort={column_name}:asc;{column_name}:desc, name=test]].

This shortcode would create a PHP variable ( $test ) which can be processed in a snippet. All parameters in the
shortcode are optional. The {name of list} should be substituted with the actual name of the list. IE..contacts.

### The Parameters

- The per_page parameter specifies how many list items to display per page. By default, all lists are loaded into a
Pagination object.

- The limit parameter will limit the total number of records returned from the list. Set value to -1 to return all
records (this is the default).

- The sort parameter specifies the sort order of the list contents. Sort by multiple columns by delimiting the values
with a semicolon (;).

- The name parameter is what the PHP variable will be set to. The default value is the name of the list.
