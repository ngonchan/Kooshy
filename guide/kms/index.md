# Kooshy (KMS) /ˈko͝oSHē/

KMS is a content management system designed as an addon module for the Kohana framework. It has been designed to help
you manage your site content easily, with as little hassle as possible. KMS is a constant work in progress and we are
always looking to get better.

## Getting started

Before you use KMS, you must enable the required modules

	Kohana::modules(array(
		...
		'pagination' => MODPATH.'pagination',
		'database' => MODPATH.'database',
		'orm' => MODPATH.'orm',
		'kms' => MODPATH.'kms',
		...
	));

[!!] The pagination, database, and orm modules are required for the ORM module to work. Of course the database and
pagination modules have to be configured as needed.

## Installation

KMS has an automated installation script to aid during the installation process. To get started, you just need to
add KMS as a Kohana Module. The install script will startup when you open the site in your browser. The first step in
the setup process will ensure your environment is setup properly to run KMS.

[Advanced Setup](advanced) instructions are also available if you would like to install KMS manually

[!!] Check out the [roadmap](http://cognitived.com/kms/roadmap/) for when more features will become available!