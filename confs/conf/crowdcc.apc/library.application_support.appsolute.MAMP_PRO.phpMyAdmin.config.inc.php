<?php

/* $Id: config.inc.php,v 2.52 2005/03/16 17:22:08 lem9 Exp $ */
// vim: expandtab sw=4 ts=4 sts=4:

/**
 * phpMyAdmin Configuration File
 *
 * All directives are explained in Documentation.html
 */

/**
 * Sets the php error reporting - Please do not change this line!
 */
if (!isset($old_error_reporting)) {
    error_reporting(E_ALL);
    @ini_set('display_errors', '1');
}

$cfg['VersionCheck'] = FALSE;

/**
 * Your phpMyAdmin url
 *
 * Complete the variable below with the full url ie
 *    http://www.your_web.net/path_to_your_phpMyAdmin_directory/
 *
 * It must contain characters that are valid for a URL, and the path is
 * case sensitive on some Web servers, for example Unix-based servers.
 *
 * In most cases you can leave this variable empty, as the correct value
 * will be detected automatically. However, we recommend that you do
 * test to see that the auto-detection code works in your system. A good
 * test is to browse a table, then edit a row and save it.  There will be
 * an error message if phpMyAdmin cannot auto-detect the correct value.
 *
 * If the auto-detection code does work properly, you can set to TRUE the
 * $cfg['PmaAbsoluteUri_DisableWarning'] variable below.
 */
$cfg['PmaAbsoluteUri'] = '';


/**
 * Disable the default warning about $cfg['PmaAbsoluteUri'] not being set
 * You should use this if and ONLY if the PmaAbsoluteUri auto-detection
 * works perfectly.
 */
$cfg['PmaAbsoluteUri_DisableWarning'] = TRUE;

/**
 * Disable the default warning that is displayed on the DB Details Structure page if
 * any of the required Tables for the relationfeatures could not be found
 */
$cfg['PmaNoRelation_DisableWarning']  = TRUE;

/**
 * The 'cookie' auth_type uses blowfish algorithm to encrypt the password. If
 * at least one server configuration uses 'cookie' auth_type, enter here a
 * passphrase that will be used by blowfish. The maximum length seems to be 46
 * characters.
 */
$cfg['blowfish_secret'] = 'nCXXe8{]dGbvP*i+vI56GslP-LWR{$K)x8L~M^5L';

/**
 * Server(s) configuration
 */
$i = 0;
// The $cfg['Servers'] array starts with $cfg['Servers'][1].  Do not use $cfg['Servers'][0].
// You can disable a server config entry by setting host to ''.
$i++;
$cfg['Servers'][$i]['host']          = 'localhost'; // MySQL hostname or IP address
$cfg['Servers'][$i]['port']          = '3306';      // MySQL port - leave blank for default port
$cfg['Servers'][$i]['socket'] = '/Applications/MAMP/tmp/mysql/mysql.sock';  // Path to the socket - leave blank for default socket
$cfg['Servers'][$i]['connect_type']  = 'tcp';       // How to connect to MySQL server ('tcp' or 'socket')
$cfg['Servers'][$i]['extension']     = 'mysql';     // The php MySQL extension to use ('mysql' or 'mysqli')
$cfg['Servers'][$i]['compress']      = FALSE;       // Use compressed protocol for the MySQL connection
                                                    // (requires PHP >= 4.3.0)
$cfg['Servers'][$i]['controluser']   = '';          // MySQL control user settings
                                                    // (this user must have read-only
$cfg['Servers'][$i]['controlpass']   = '';          // access to the "mysql/user"
                                                    // and "mysql/db" tables).
                                                    // The controluser is also
                                                    // used for all relational
                                                    // features (pmadb)

$cfg['Servers'][$i]['AllowNoPassword'] = TRUE;


$cfg['Servers'][$i]['auth_type']     = 'config';    // Authentication method (config, http or cookie based)?
$cfg['Servers'][$i]['user']          = 'root';                         // MySQL user
$cfg['Servers'][$i]['password']      = 'r0ll1ngSt0nEl1veSess10n';      // MySQL password


/*
$cfg['Servers'][$i]['auth_type']     = 'cookie';    // Authentication method (config, http or cookie based)?
$cfg['Servers'][$i]['user']          = 'root';                        // MySQL user
$cfg['Servers'][$i]['password']      = 'r0ll1ngSt0nEl1veSess10n';     // MySQL password
*/
                                                
$cfg['Servers'][$i]['only_db']       = '';          // If set to a db-name, only
                                                    // this db is displayed in left frame
                                                    // It may also be an array of db-names, where sorting order is relevant.
$cfg['Servers'][$i]['verbose']       = '';          // Verbose name for this host - leave blank to show the hostname

$cfg['Servers'][$i]['pmadb']         = '';          // Database used for Relation, Bookmark and PDF Features
                                                    // (see scripts/create_tables.sql)
                                                    //   - leave blank for no support
                                                    //     DEFAULT: 'phpmyadmin'
$cfg['Servers'][$i]['bookmarktable'] = '';          // Bookmark table
                                                    //   - leave blank for no bookmark support
                                                    //     DEFAULT: 'pma_bookmark'
$cfg['Servers'][$i]['relation']      = '';          // table to describe the relation between links (see doc)
                                                    //   - leave blank for no relation-links support
                                                    //     DEFAULT: 'pma_relation'
$cfg['Servers'][$i]['table_info']    = '';          // table to describe the display fields
                                                    //   - leave blank for no display fields support
                                                    //     DEFAULT: 'pma_table_info'
$cfg['Servers'][$i]['table_coords']  = '';          // table to describe the tables position for the PDF schema
                                                    //   - leave blank for no PDF schema support
                                                    //     DEFAULT: 'pma_table_coords'
$cfg['Servers'][$i]['pdf_pages']     = '';          // table to describe pages of relationpdf
                                                    //   - leave blank if you don't want to use this
                                                    //     DEFAULT: 'pma_pdf_pages'
$cfg['Servers'][$i]['column_info']   = '';          // table to store column information
                                                    //   - leave blank for no column comments/mime types
                                                    //     DEFAULT: 'pma_column_info'
$cfg['Servers'][$i]['history']       = '';          // table to store SQL history
                                                    //   - leave blank for no SQL query history
                                                    //     DEFAULT: 'pma_history'
$cfg['Servers'][$i]['verbose_check'] = TRUE;        // set to FALSE if you know that your pma_* tables
                                                    // are up to date. This prevents compatibility
                                                    // checks and thereby increases performance.
$cfg['Servers'][$i]['AllowRoot']     = TRUE;        // whether to allow root login
$cfg['Servers'][$i]['AllowDeny']['order']           // Host authentication order, leave blank to not use
                                     = '';
$cfg['Servers'][$i]['AllowDeny']['rules']           // Host authentication rules, leave blank for defaults
                                     = array();


// If you have more than one server configured, you can set $cfg['ServerDefault']
// to any one of them to autoconnect to that server when phpMyAdmin is started,
// or set it to 0 to be given a list of servers without logging in
// If you have only one server configured, $cfg['ServerDefault'] *MUST* be
// set to that server.
$cfg['ServerDefault'] = 1;              // Default server (0 = no default server)
$cfg['Server']        = '';
unset($cfg['Servers'][0]);


/**
 * Other core phpMyAdmin settings
 */
$cfg['OBGzip']                  = 'auto'; // use GZIP output buffering if possible (TRUE|FALSE|'auto')
$cfg['PersistentConnections']   = FALSE;  // use persistent connections to MySQL database
$cfg['ExecTimeLimit']           = 300;    // maximum execution time in seconds (0 for no limit)
$cfg['SkipLockedTables']        = FALSE;  // mark used tables, make possible to show
                                          // locked tables (since MySQL 3.23.30)
$cfg['ShowSQL']                 = TRUE;   // show SQL queries as run
$cfg['AllowUserDropDatabase']   = FALSE;  // show a 'Drop database' link to normal users
$cfg['Confirm']                 = TRUE;   // confirm 'DROP TABLE' & 'DROP DATABASE'
$cfg['LoginCookieRecall']       = TRUE;   // recall previous login in cookie auth. mode or not
$cfg['LoginCookieValidity']     = 1440;   // validity of cookie login (in seconds)
$cfg['UseDbSearch']             = TRUE;   // whether to enable the "database search" feature
                                          // or not
$cfg['IgnoreMultiSubmitErrors'] = FALSE;  // if set to true, PMA continues computing multiple-statement queries
                                          // even if one of the queries failed
$cfg['VerboseMultiSubmit']      = TRUE;   // if set to true, PMA will show the affected rows of EACH statement on
                                          // multiple-statement queries. See the read_dump.php file for hardcoded
                                          // defaults on how many queries a statement may contain!
$cfg['AllowArbitraryServer']    = FALSE;  // allow login to any user entered server in cookie based auth

/**
 * Tabs display settings
 */
$cfg['LightTabs']             = FALSE;    // use graphically less intense menu tabs
$cfg['PropertiesIconic']      = TRUE;     // Use icons instead of text for the table display of a database (TRUE|FALSE|'both')
$cfg['PropertiesNumColumns']  = 1;        // How many columns should be used for table display of a database?
                                          // (a value larger than 1 results in some information being hidden)

$cfg['DefaultTabServer']      = 'index.php';
                                          // Possible values:
                                          // 'main.php' = the welcome page
                                          // (recommended for multiuser setups)
                                          // 'server_databases.php' = list of databases
                                          // 'server_status.php' = runtime information
                                          // 'server_variables.php' = MySQL server variables
                                          // 'server_privileges.php' = user management
                                          // 'server_processlist.php' = process list
$cfg['DefaultTabDatabase']    = 'db_details_structure.php';
                                          // Possible values:
                                          // 'db_details_structure.php' = tables list
                                          // 'db_details.php' = sql form
                                          // 'db_search.php' = search query
                                          // 'db_operations.php' = operations on database
$cfg['DefaultTabTable']       = 'sql.php';  
                                          // Possible values:
                                          // 'sql.php' = browse page
                                          // 'tbl_properties_structure.php' = fields list
                                          // 'tbl_properties.php' = sql form
                                          // 'tbl_select.php = select page * search page
                                          // 'tbl_change.php = insert row page

/**
 * Unset magic_quotes_runtime - do not change!
 */
// set_magic_quotes_runtime(0);
ini_set('magic_quotes_runtime', 0);

$cfg['AllowThirdPartyFraming'] = TRUE;

/**
 * File Revision - do not change either!
 */
$cfg['FileRevision'] = '$Revision: 2.52 $';

/*
 * phpMyAdmin configuration storage settings.
 */

/* User used to manipulate with storage */
// $cfg['Servers'][$i]['controlhost'] = '';
// $cfg['Servers'][$i]['controlport'] = '';
// $cfg['Servers'][$i]['controluser'] = 'pma';
// $cfg['Servers'][$i]['controlpass'] = 'pmapass';

/* Storage database and tables */
// $cfg['Servers'][$i]['pmadb'] = 'phpmyadmin';
// $cfg['Servers'][$i]['bookmarktable'] = 'pma__bookmark';
// $cfg['Servers'][$i]['relation'] = 'pma__relation';
// $cfg['Servers'][$i]['table_info'] = 'pma__table_info';
// $cfg['Servers'][$i]['table_coords'] = 'pma__table_coords';
// $cfg['Servers'][$i]['pdf_pages'] = 'pma__pdf_pages';
// $cfg['Servers'][$i]['column_info'] = 'pma__column_info';
// $cfg['Servers'][$i]['history'] = 'pma__history';
// $cfg['Servers'][$i]['table_uiprefs'] = 'pma__table_uiprefs';
// $cfg['Servers'][$i]['tracking'] = 'pma__tracking';
// $cfg['Servers'][$i]['userconfig'] = 'pma__userconfig';
// $cfg['Servers'][$i]['recent'] = 'pma__recent';
// $cfg['Servers'][$i]['favorite'] = 'pma__favorite';
// $cfg['Servers'][$i]['users'] = 'pma__users';
// $cfg['Servers'][$i]['usergroups'] = 'pma__usergroups';
// $cfg['Servers'][$i]['navigationhiding'] = 'pma__navigationhiding';
// $cfg['Servers'][$i]['savedsearches'] = 'pma__savedsearches';
// $cfg['Servers'][$i]['central_columns'] = 'pma__central_columns';
/* Contrib / Swekey authentication */
// $cfg['Servers'][$i]['auth_swekey_config'] = '/etc/swekey-pma.conf';

/*
 * End of servers configuration
 */

/*
 * Directories for saving/loading files from server
 */
$cfg['UploadDir'] = '';
$cfg['SaveDir'] = '';

/*
 * Directory for saving session files
 */

// $cfg['SessionSavePath'] = '/Applications/MAMP/tmp/php';

/**
 * Whether to display icons or text or both icons and text in table row
 * action segment. Value can be either of 'icons', 'text' or 'both'.
 */
//$cfg['RowActionType'] = 'both';

/**
 * Defines whether a user should be displayed a "show all (records)"
 * button in browse mode or not.
 * default = false
 */
//$cfg['ShowAll'] = true;

/**
 * Number of rows displayed when browsing a result set. If the result
 * set contains more rows, "Previous" and "Next".
 * default = 30
 */
//$cfg['MaxRows'] = 50;

/**
 * disallow editing of binary fields
 * valid values are:
 *   false    allow editing
 *   'blob'   allow editing except for BLOB fields
 *   'noblob' disallow editing except for BLOB fields
 *   'all'    disallow editing
 * default = blob
 */
//$cfg['ProtectBinary'] = 'false';

/**
 * Default language to use, if not browser-defined or user-defined
 * (you find all languages in the locale folder)
 * uncomment the desired line:
 * default = 'en'
 */
//$cfg['DefaultLang'] = 'en';
//$cfg['DefaultLang'] = 'de';

/**
 * How many columns should be used for table display of a database?
 * (a value larger than 1 results in some information being hidden)
 * default = 1
 */
//$cfg['PropertiesNumColumns'] = 2;

/**
 * Set to true if you want DB-based query history.If false, this utilizes
 * JS-routines to display query history (lost by window close)
 *
 * This requires configuration storage enabled, see above.
 * default = false
 */
//$cfg['QueryHistoryDB'] = true;

/**
 * When using DB-based query history, how many entries should be kept?
 *
 * default = 25
 */
//$cfg['QueryHistoryMax'] = 100;

/**
 * Should error reporting be enabled for JavaScript errors
 *
 * default = 'ask'
 */
//$cfg['SendErrorReports'] = 'ask';

/*
 * You can find more configuration options in the documentation
 * in the doc/ folder or at <http://docs.phpmyadmin.net/>.
 */

/* Add the following to the end of the file: */
# local server * add following after all the existing server configurations:
$cfg['Servers'][$i]['verbose'] = 'local crowdcc.apc';
$cfg['Servers'][$i]['host'] = 'localhost';
$cfg['Servers'][$i]['port'] = '3306';

$cfg['Servers'][$i]['user']          = 'ccsauce';                     // MySQL user
$cfg['Servers'][$i]['password']      = 'ketchUpisc00lHP1sbe110r';     // MySQL pass

$cfg['Servers'][$i]['connect_type'] = 'tcp';
$cfg['Servers'][$i]['extension'] = 'mysqli';
$cfg['Servers'][$i]['compress'] = FALSE;
// $cfg['Servers'][$i]['auth_type'] = 'cookie';      // enable for log-in option * security * delete * MySQL user =''; * MySQL pass ='';
$cfg['Servers'][$i]['auth_type']     = 'config';     // enable for log-in option * security * delete * $cfg['Servers'][$i]['auth_type'] = 'config'; 
$i++;

/* remote server * change this to whatever you like. */
$cfg['Servers'][$i]['verbose'] = 'stage crowdcc.dev';
$cfg['Servers'][$i]['host'] = '127.0.0.1';
$cfg['Servers'][$i]['port'] = '3307';

$cfg['Servers'][$i]['user']          = 'ccsauce';                     // MySQL user
$cfg['Servers'][$i]['password']      = 'ketchUpisc00lHP1sbe110r';     // MySQL pass

$cfg['Servers'][$i]['connect_type'] = 'tcp';
$cfg['Servers'][$i]['extension'] = 'mysqli';
$cfg['Servers'][$i]['compress'] = FALSE;
// $cfg['Servers'][$i]['auth_type'] = 'cookie';     // enable for log-in option * security * delete * MySQL user =''; * MySQL pass ='';
$cfg['Servers'][$i]['auth_type']     = 'config';    // enable for log-in option * security * delete * $cfg['Servers'][$i]['auth_type'] = 'config'; 
$i++;

/* remote server * change this to whatever you like. */
$cfg['Servers'][$i]['verbose'] = 'prod crowdcc.com';
$cfg['Servers'][$i]['host'] = '127.0.0.1';
$cfg['Servers'][$i]['port'] = '3307';

$cfg['Servers'][$i]['user']          = 'ccsauce';                     // MySQL user
$cfg['Servers'][$i]['password']      = 'ketchUpisc00lHP1sbe110r';     // MySQL pass

$cfg['Servers'][$i]['connect_type'] = 'tcp';
$cfg['Servers'][$i]['extension'] = 'mysqli';
$cfg['Servers'][$i]['compress'] = FALSE;
// $cfg['Servers'][$i]['auth_type'] = 'cookie';     // enable for log-in option * security * delete * MySQL user =''; * MySQL pass ='';
$cfg['Servers'][$i]['auth_type']     = 'config';    // enable for log-in option * security * delete * $cfg['Servers'][$i]['auth_type'] = 'config'; 
$i++;

?>