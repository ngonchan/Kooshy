<?php defined('SYSPATH') or die('No direct script access.');
// PROCESSING
$step = arr::get($_GET, 'step', 1);

$process = arr::get($_REQUEST, 'process');
switch ($process) {
	case 'database':
		$link = @mysql_connect(
			arr::get($_POST, 'dbhost'),
			arr::get($_POST, 'uname'),
			arr::get($_POST, 'pwd')
		);
		if (!$link) KMS::stop('<strong>ERROR:</strong> Could not connect to MySQL database. Click back to verify your MySQL settings.<br /><br /><strong>MySQL Error:</strong> ' . mysql_error());
		$db_selected = mysql_select_db(arr::get($_POST, 'dbname'), $link);
		$error = mysql_error();
		mysql_close($link);
		if (!$db_selected) KMS::stop('<strong>ERROR:</strong> Could not connect to MySQL database. Click back to verify your MySQL settings.<br /><br /><strong>MySQL Error:</strong> ' . $error);
		Session::instance()->set('kms-database', $_POST);
		$step = 3;
		break;
	case 'kms-config':
		Session::instance()->set('kms-config', $_POST);
		$step = 4;
		break;
	case 'install':
		$kms_dir = arr::get(kohana::modules(), 'kms');
		$db_schema = $kms_dir . 'assets/schema/install.sql';
		if (!file_exists($db_schema)) KMS::stop ( 'Unable to load database schema for installation' );
		$db_config = $kms_dir . 'config/database.php';
		$kms_config = $kms_dir . 'config/kms.php';

		// get db queries for install
		$db_schema = file($db_schema);
		foreach ($db_schema as $key => $line) {
			$line = trim(preg_replace('/\n/', ' ', $line));
			if (preg_match('/^--/', $line) || empty($line))
				unset($db_schema[$key]);
			else $db_schema[$key] = $line;
		}
		$db_schema = explode(';', implode('', $db_schema));
		array_pop($db_schema);

		// create kms configuration
		$data = Kohana::FILE_SECURITY . "\n
return array (
	'session_key' => '" . arr::get(Session::instance()->get('kms-config'), 'session_key') . "',
);";
		file_put_contents($kms_config, $data);
		chmod($kms_config, 0777);

		// create database configuration
		$data = Kohana::FILE_SECURITY . "\n
return array (
	'kms' => array (
		'type'         => 'mysql',
		'connection'   => array (
			'hostname'   => '" . arr::get(Session::instance()->get('kms-database'), 'dbhost') . "',
			'database'   => '" . arr::get(Session::instance()->get('kms-database'), 'dbname') . "',
			'username'   => '" . arr::get(Session::instance()->get('kms-database'), 'uname') . "',
			'password'   => '" . arr::get(Session::instance()->get('kms-database'), 'pwd') . "',
			'persistent' => FALSE,
		),
		'table_prefix' => '',
		'charset'      => 'utf8',
		'caching'      => FALSE,
		'profiling'    => TRUE,
	),
);";
		file_put_contents($db_config, $data);
		chmod($db_config, 0777);

		// create database tables
		$link = @mysql_connect(
			arr::get(Session::instance()->get('kms-database'), 'dbhost'),
			arr::get(Session::instance()->get('kms-database'), 'uname'),
			arr::get(Session::instance()->get('kms-database'), 'pwd')
		);
		if (!$link) KMS::stop('<strong>ERROR:</strong> Could not connect to MySQL database. Click back to verify your MySQL settings.<br /><br /><strong>MySQL Error:</strong> ' . mysql_error(), $_POST);
		$db = mysql_select_db(arr::get(Session::instance()->get('kms-database'), 'dbname'), $link);
		if (!$db) {
			$error = mysql_error();
			mysql_close($link);
			KMS::stop('<strong>ERROR:</strong> Could not connect to MySQL database. Click back to verify your MySQL settings.<br /><br /><strong>MySQL Error:</strong> ' . $error, $_POST);
		}
		foreach ($db_schema as $sql) {
			$response = @mysql_query($sql, $link);
			if (!$response) {
				$error = mysql_error();
				mysql_close($link);
				KMS::stop('<strong>ERROR:</strong> Could not connect to MySQL database. Click back to verify your MySQL settings.<br /><br /><strong>MySQL Error:</strong> ' . $error);
			}
		}
		mysql_close($link);

		// add site and user data
		$site = ORM::factory('site');
		$site->domain = arr::get(Session::instance()->get('kms-config'), 'domain');
		$site->description = arr::get(Session::instance()->get('kms-config'), 'domain_description');
		$site->save();
		$site_id = $site->id;

		$user = ORM::factory('user');
		$user->username = arr::get(Session::instance()->get('kms-config'), 'admin');
		$user->password = sha1(arr::get(Session::instance()->get('kms-config'), 'admin_pwd'));
		$user->first_name = arr::get(Session::instance()->get('kms-config'), 'first_name');
		$user->last_name = arr::get(Session::instance()->get('kms-config'), 'last_name');
		$user->email = arr::get(Session::instance()->get('kms-config'), 'email');
		$user->active = TRUE;
		$user->super = TRUE;
		$user->save();
		$user_id = $user->id;

		Session::instance()->destroy();
		$step = 5;
		break;
}

if ($process !== NULL) {
	header('location: ' . kohana::$base_url . '?step=' . $step);
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Setup / Installation (KMS)</title>
		<?php echo html::style('kms-asset/css/setup.css', array('media' => 'screen')) ?>

	</head>
	<body>
		<h1 id="logo">KMS Setup<?php if ($step < 5) { ?> - Step <?php echo $step ?> of 4<?php } ?></h1>

		<?php
		switch ($step) {
			case 1: setup_one(); break;
			case 2: setup_two(); break;
			case 3: setup_three(); break;
			case 4: setup_four(); break;
			case 5: setup_five(); break;
		}
		?>

	</body>
</html>
<?php
exit;

function setup_one() {
	$failed = FALSE;
	?>
<p>
	Welcome to the Kooshy (KMS). The following tests have been run to determine if
	<a href="http://cognitived.com/kms/">KMS</a> will work in your environment. If any of the tests have failed,
	you will need to take appropriate action before continuing to use KMS.
</p>

<table cellspacing="0">
	<tr>
		<th>PHP Version</th>
		<?php if (version_compare(PHP_VERSION, '5.2.14', '>=')) { ?>
			<td class="pass"><?php echo PHP_VERSION ?></td>
		<?php } else { $failed = TRUE ?>
			<td class="fail">Kohana requires PHP 5.2.14 or newer, this version is <?php echo PHP_VERSION ?>.</td>
		<?php } ?>
	</tr>
	<tr>
		<th>Apache</th>
		<?php if (function_exists('apache_get_version')) { ?>
			<td class="pass">Is Running</td>
		<?php } else { $failed = TRUE ?>
			<td class="fail">KMS requires Apache 2.2 or newer. Your server does not appear to be running Apache.</td>
		<?php } ?>
	</tr>
	<?php if (function_exists('apache_get_version')) { ?>
	<tr>
		<th>Apache Version</th>
		<?php
		$apache_version = preg_replace('/^.+?\/([\.\d]+).+$/', '$1', apache_get_version());
		if (version_compare($apache_version, '2.2', '>=')) {
		?>
			<td class="pass"><?php echo $apache_version ?></td>
		<?php } else { $failed = TRUE ?>
			<td class="fail">KMS requires Apache 2.2 or newer, this version is <?php echo $apache_version ?>.</td>
		<?php } ?>
	</tr>
	<tr>
		<th>Apache Rewrite</th>
		<?php if ( in_array('mod_rewrite', apache_get_modules()) ) { ?>
			<td class="pass">mod_rewrite installed</td>
		<?php } else { $failed = TRUE ?>
			<td class="fail">KMS requires the Apache Module mod_rewrite. Your server does not appear to be configured with mod_rewrite.</td>
		<?php } ?>
	</tr>
	<?php } else $fail = TRUE; ?>
	<tr>
		<th>Kohana Database Module</th>
		<?php if ( in_array('database', array_keys(kohana::modules())) ) { ?>
			<td class="pass">Installed</td>
		<?php } else { $failed = TRUE ?>
			<td class="fail">KMS requires the Kohana Database Module. Your Kohana environment does not appear to it activated.</td>
		<?php } ?>
	</tr>
	<tr>
		<th>Kohana ORM Module</th>
		<?php if ( in_array('orm', array_keys(kohana::modules())) ) { ?>
			<td class="pass">Installed</td>
		<?php } else { $failed = TRUE ?>
			<td class="fail">KMS requires the Kohana ORM Module. Your Kohana environment does not appear to it activated.</td>
		<?php } ?>
	</tr>
	<tr>
		<th>Kohana Pagination Module</th>
		<?php if ( in_array('pagination', array_keys(kohana::modules())) ) { ?>
			<td class="pass">Installed</td>
		<?php } else { $failed = TRUE ?>
			<td class="fail">KMS requires the Kohana ORM Module. Your Kohana environment does not appear to it activated.</td>
		<?php } ?>
	</tr>
	<tr>
		<th>Configuration Directory</th>
		<?php $modules = kohana::modules(); $config_dir = $modules['kms'] . 'config/' ?>
		<?php if (is_dir($config_dir) && is_writable($config_dir)): ?>
			<td class="pass"><?php echo $config_dir ?></td>
		<?php else: $failed = TRUE ?>
			<td class="fail">The <code><?php echo $config_dir ?></code> directory is not writable.</td>
		<?php endif ?>
	</tr>
</table>

<?php if ($failed === TRUE) { ?>
<p id="results" class="fail">The Kooshy (KMS) may not work correctly with your environment.</p>
<?php } else { ?>
<p id="results" class="pass">Your environment passed all requirements.</p>

<h2>Optional Tests</h2>
<p>
	The following are not required to run the KMS, but if enabled can provide access to additional functionality.
</p>

<table>
	<tr>
		<th>Kohana Userguide Module</th>
		<?php if ( in_array('userguide', array_keys(kohana::modules())) ) { ?>
			<td class="pass">Installed</td>
		<?php } else { $failed = TRUE ?>
			<td class="fail">KMS documentation can be found inside the userguide.</td>
		<?php } ?>
	</tr>
	<tr>
		<th>Kohana Unittest Module</th>
		<?php if ( in_array('unittest', array_keys(kohana::modules())) ) { ?>
			<td class="pass">Installed</td>
		<?php } else { $failed = TRUE ?>
			<td class="fail">KMS unit tests can run inside the unittest module.</td>
		<?php } ?>
	</tr>
</table>

<h2>Required Information</h2>
<p>
	Before getting started, we need some information on the database. You will need to know the following items	before proceeding.
</p>
<ol>
	<li>Database name</li>
	<li>Database username</li>
	<li>Database password</li>
	<li>Database host</li>
</ol>
<p>
	In all likelihood, these items were supplied to you by your Web Host. If you do not have this information, then you
	will need to contact them before you can continue. If you&#8217;re all ready&hellip;
</p>


<p class="step"><a href="<?php echo Kohana::$base_url ?>?step=2<?php if ( isset( $_GET['noapi'] ) ) echo '&amp;noapi'; ?>" class="button">Let&#8217;s go!</a></p>
<?php } ?>
<?php
}

function setup_two() {
	?>
<form method="post" action="<?php echo Kohana::$base_url ?>">
	<input type="hidden" name="process" value="database" />
	<p>Below you should enter your database connection details. If you're not sure about these, contact your host.</p>
	<table class="form-table">
		<tr>
			<th scope="row"><label for="dbname">Database Name</label></th>
			<td><input name="dbname" id="dbname" type="text" size="25" value="<?php echo arr::get(Session::instance()->get('kms-database'), 'dbname', 'kms') ?>" /></td>
			<td class="details">The name of the database you want to run KMS in.</td>
		</tr>
		<tr>
			<th scope="row"><label for="uname">User Name</label></th>
			<td><input name="uname" id="uname" type="text" size="25" value="<?php echo arr::get(Session::instance()->get('kms-database'), 'uname', 'username') ?>" /></td>
			<td>Your MySQL username</td>
		</tr>
		<tr>
			<th scope="row"><label for="pwd">Password</label></th>
			<td><input name="pwd" id="pwd" type="text" size="25" value="<?php echo arr::get(Session::instance()->get('kms-database'), 'pwd', 'password') ?>" /></td>
			<td>...and MySQL password.</td>
		</tr>
		<tr>
			<th scope="row"><label for="dbhost">Database Host</label></th>
			<td><input name="dbhost" id="dbhost" type="text" size="25" value="<?php echo arr::get(Session::instance()->get('kms-database'), 'dbhost', 'localhost') ?>" /></td>
			<td class="details">You should be able to get this info from your web host, if <code>localhost</code> does not work.</td>
		</tr>
	</table>
	<p class="step"><input name="submit" type="submit" value="Submit" class="button" /></p>
</form>
<?php
}

function setup_three() {
	?>
<form method="post" action="<?php echo Kohana::$base_url ?>">
	<input type="hidden" name="process" value="kms-config" />
	<p>Below is the required configuration data for KMS.</p>
	<table>
		<tr>
			<th scope="row"><label for="domain">First Domain</label></th>
			<td><input name="domain" id="domain" type="hidden" size="25" value="<?php echo arr::get($_SERVER, 'HTTP_HOST') ?>" />http://<?php echo arr::get($_SERVER, 'HTTP_HOST') ?>/</td>
			<td class="details">The first domain you are setting up in KMS. Additional domains can be added later.</td>
		</tr>
		<tr>
			<th scope="row"><label for="domain_description">Domain Description</label></th>
			<td><input name="domain_description" id="domain_description" type="text" size="25" value="<?php echo arr::get(Session::instance()->get('kms-config'), 'domain_description', 'My First Site Setup with KMS!') ?>" /></td>
			<td class="details">A description of your KMS site.</td>
		</tr>
		<tr>
			<th scope="row"><label for="session_key">Session Name</label></th>
			<td><input name="session_key" id="session_key" type="text" size="25" value="<?php echo arr::get(Session::instance()->get('kms-config'), 'session_key', 'kms') ?>" /></td>
			<td class="details">The name of the session key for KMS to use.</td>
		</tr>
		<tr>
			<th scope="row"><label for="admin">KMS Admin User</label></th>
			<td><input name="admin" id="admin" type="text" size="25" value="<?php echo arr::get(Session::instance()->get('kms-config'), 'admin', 'admin') ?>" /></td>
			<td class="details">The username of the super administrator account. Additional accounts can be setup later.</td>
		</tr>
		<tr>
			<th scope="row"><label for="admin_pwd">KMS Admin Password</label></th>
			<td><input name="admin_pwd" id="admin_pwd" type="text" size="25" value="<?php echo arr::get(Session::instance()->get('kms-config'), 'admin_pwd', 'password') ?>" /></td>
			<td class="details">The password of the super administrator account.</td>
		</tr>
		<tr>
			<th scope="row"><label for="first_name">Admin First Name</label></th>
			<td><input name="first_name" id="first_name" type="text" size="25" value="<?php echo arr::get(Session::instance()->get('kms-config'), 'first_name', '') ?>" /></td>
			<td class="details">The first name of the super administrator account.</td>
		</tr>
		<tr>
			<th scope="row"><label for="last_name">Admin Last Name</label></th>
			<td><input name="last_name" id="last_name" type="text" size="25" value="<?php echo arr::get(Session::instance()->get('kms-config'), 'last_name', '') ?>" /></td>
			<td class="details">The last name of the super administrator account.</td>
		</tr>
		<tr>
			<th scope="row"><label for="email">Admin Email</label></th>
			<td><input name="email" id="email" type="text" size="25" value="<?php echo arr::get(Session::instance()->get('kms-config'), 'email', '') ?>" /></td>
			<td class="details">The email address of the super administrator account.</td>
		</tr>
	</table>
	<p class="step"><input name="submit" type="submit" value="Submit" class="button" /></p>
</form>
<?php
}

function setup_four() {
	?>
<h2>Review Settings</h2>
<p>
	All right! KMS now has all the information required to do the installation. Please review the details and click on
	"Run the Install" when you are ready. Click "Go Back" to change settings.
</p>
<h2>KMS Configuration</h2>
<table>
	<tr>
		<th scope="row">Domain Name</th>
		<td><?php echo arr::get(Session::instance()->get('kms-config'), 'domain') ?></td>
			<td class="details">The first domain you are setting up in KMS. Additional domains can be added later.</td>
	</tr>
	<tr>
		<th scope="row"><label for="domain_description">Domain Description</label></th>
		<td class="details"><?php echo arr::get(Session::instance()->get('kms-config'), 'domain_description') ?></td>
		<td class="details">A description of your KMS site.</td>
	</tr>
	<tr>
		<th scope="row">Session Name</th>
		<td><?php echo arr::get(Session::instance()->get('kms-config'), 'session_key') ?></td>
		<td>The name of the session key for KMS to use.</td>
	</tr>
	<tr>
		<th scope="row"><label for="admin">KMS Admin User</label></th>
		<td><?php echo arr::get(Session::instance()->get('kms-config'), 'admin') ?></td>
		<td class="details">The username of the super administrator account. Additional accounts can be setup later.</td>
	</tr>
	<tr>
		<th scope="row"><label for="admin_pwd">KMS Admin Password</label></th>
		<td><?php echo arr::get(Session::instance()->get('kms-config'), 'admin_pwd') ?></td>
		<td class="details">The password of the super administrator account.</td>
	</tr>
	<tr>
		<th scope="row"><label for="first_name">Admin First Name</label></th>
		<td><?php echo arr::get(Session::instance()->get('kms-config'), 'first_name') ?></td>
		<td class="details">The first name of the super administrator account.</td>
	</tr>
	<tr>
		<th scope="row"><label for="last_name">Admin Last Name</label></th>
		<td><?php echo arr::get(Session::instance()->get('kms-config'), 'last_name') ?></td>
		<td class="details">The last name of the super administrator account.</td>
	</tr>
	<tr>
		<th scope="row"><label for="email">Admin Email</label></th>
		<td><?php echo arr::get(Session::instance()->get('kms-config'), 'email') ?></td>
		<td class="details">The email address of the super administrator account.</td>
	</tr>
</table>
<h2>Database Configuration</h2>
<table>
	<tr>
		<th scope="row">Database Name</th>
		<td><?php echo arr::get(Session::instance()->get('kms-database'), 'dbname') ?></td>
		<td>The name of the database you want to run KMS in.</td>
	</tr>
	<tr>
		<th scope="row">User Name</th>
		<td><?php echo arr::get(Session::instance()->get('kms-database'), 'uname') ?></td>
		<td>Your MySQL username</td>
	</tr>
	<tr>
		<th scope="row">Password</th>
		<td><?php echo arr::get(Session::instance()->get('kms-database'), 'pwd') ?></td>
		<td>...and MySQL password.</td>
	</tr>
	<tr>
		<th scope="row">Database Host</th>
		<td><?php echo arr::get(Session::instance()->get('kms-database'), 'dbhost') ?></td>
		<td class="details">You should be able to get this info from your web host, if <code>localhost</code> does not work.</td>
	</tr>
</table>
<p class="step">
	<a href="<?php echo kohana::$base_url ?>?process=install" class="button">Run the Install</a>&nbsp;&nbsp;&nbsp;
	<a href="<?php echo kohana::$base_url ?>?step=2" class="button">Go Back</a>
</p>
<?php
}

function setup_five() {
	$kms_file = arr::get(kohana::modules(), 'kms') . 'config/.kms';
	file_put_contents($kms_file, 'KMS successfully installed on :: ' . date('Y-m-d H:i:s'));
	chmod($kms_file, 0777);
	?>
<h2>Setup Complete!</h2>
<p>
	KMS has been successfully setup. You can now login to the administration section of your site using the link below
</p>
<p>
	<?php echo html::anchor(Route::url('kms-admin', array('action'=>'login')), 'Login', array('class' => 'button')) ?>
</p>
<?php
}
