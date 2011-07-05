# Advanced Setup (KMS)

KMS has an [automatic install script](./#installation) and it is recommended that you use that installation script.
However, this guide will overview setting KMS up manually. Please be sure to read the documentation fully before
attempting to do a manual installation of KMS.

For the purpose of this example, we will be using the domain http://default.kms/.

1. Download and extract kms module into your Kohana module’s directory.
1. Create a MySQL database for the application to use. At this time, it is recommended that KMS have its own database.
1. Edit the kms and database configuration files.
1. Run the SQL that is included in /kms/assets/schema directory.
1. Add domain to the sites table in the database.
1. Review the default roles in the roles database.
1. Add a user to the users table. Use SHA1 encryption for the password.
1. Add the newly created user to the site_users table and assign it a role of Administrator (see roles table for role_id).
1. Load the kms module in your application bootstrap.php file.
1. Navigate to the admin login page. http://default.kms/kms/admin
1. Login using your newly created user.
1. Create a site template in the template section.
1. Create site variables as needed. < optional >
1. Create content pages in the content section.
1. Once a content page is published, you can navigate to it.
1. Enjoy / [Report Bugs](http://cognitived.com/kms/contact/) / [Send Feedback](http://cognitived.com/kms/contact/)!

## Additional Options / Information

- Snippets can be created in the snippet tables. Snippets are global to all sites so they also have to be allowed to run on a site. Currently this is done by adding the snippet to the site_snippets table. Once it is in here, it is authorized to run on the site. Inside the administration of the site, you can toggle snippets on and off as needed.
- Chunks are setup the exact same way as snippets except you mark the eval column in the snippets table to 0 (or false).
- Templates can be added in the administration area, but they are also global to all sites. Once created, a template can only be removed via the database at this time. An administrator can remove a template from inside the administration section, but this will only remove it from the list of available templates for that particular site.