# Configuration (KMS)

KMS has a simple configuration file. You will need to specify the session key value inside the /kms/config/
directory. If the key `kms` does not conflict with any other session values, you can leave the default value.

	return array (
		'session_key' => 'kms',
	);

## Database

Since KMS uses the Kohana database module, you will need to setup the database configuration file to point to the
appropriate database with the needed username and password. The database key will need to be updated in the init.php
file for KMS if you use a different database key than `kms`.

[!!] Both the KMS and database configuration files are created by the install