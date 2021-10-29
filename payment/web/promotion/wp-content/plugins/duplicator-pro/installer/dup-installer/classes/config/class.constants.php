<?php
defined('ABSPATH') || defined('DUPXABSPATH') || exit;

require_once(DUPX_INIT.'/classes/class.s3.func.php');

/**
 * Class used to group all global constants
 *
 * Standard: PSR-2
 * @link http://www.php-fig.org/psr/psr-2 Full Documentation
 *
 * @package SC\DUPX\Constants
 *
 */
class DUPX_MultisiteMode
{

    const SingleSite   = -1;
    const Standalone   = 0;
    const Subdomain    = 1;
    const Subdirectory = 2;

}

class DUPX_Constants
{

    const DEFAULT_MAX_STRLEN_SERIALIZED_CHECK_IN_M = 4; // 0 no limit
    const CHUNK_EXTRACTION_TIMEOUT_TIME            = 10;
    const CHUNK_MAX_TIMEOUT_TIME                   = 17; // 15 seconds + 2 seconds

    /**
     *
     * @var int
     */
    public static $maxStrlenSerializeCheck = self::DEFAULT_MAX_STRLEN_SERIALIZED_CHECK;

    /**
     * Init method used to auto initialize the global params 
     * This function init all params before read from request
     *
     * @return null
     */
    public static function init()
    {
        $GLOBALS['FAQ_URL'] = 'https://snapcreek.com/duplicator/docs/faqs-tech';

        //DATABASE SETUP: all time in seconds
        //max_allowed_packet: max value 1073741824 (1268MB) see my.ini
        $GLOBALS['DB_MAX_TIME']                           = 5000;
        $GLOBALS['DATABASE_PAGE_SIZE']                    = 3500;
        $GLOBALS['DB_MAX_PACKETS']                        = 268435456;
        $GLOBALS['DBCHARSET_DEFAULT']                     = 'utf8';
        $GLOBALS['DBCOLLATE_DEFAULT']                     = 'utf8_general_ci';
        $GLOBALS['DB_RENAME_PREFIX']                      = 'x-bak-'.@date("dHis").'__';
        $GLOBALS['DB_INSTALL_MULTI_THREADED_MAX_RETRIES'] = 3;

        if (!defined('MAX_SITES_TO_DEFAULT_ENABLE_CORSS_SEARCH')) {
            define('MAX_SITES_TO_DEFAULT_ENABLE_CORSS_SEARCH', 10);
        }

        //UPDATE TABLE SETTINGS
        $GLOBALS['REPLACE_LIST'] = array();
        $GLOBALS['DEBUG_JS']     = false;

        //CONSTANTS
        define("DUPLICATOR_PRO_INIT", 1);
        if (!defined("DUPLICATOR_PRO_SSDIR_NAME")) {
            define("DUPLICATOR_PRO_SSDIR_NAME", 'wp-snapshots-dup-pro');  //This should match DUPLICATOR_PRO_SSDIR_NAME in duplicator.php
        }

        //GLOBALS
        $GLOBALS['LOG_FILE_NAME']                          = 'dup-installer-log__'.DUPX_Package::getPackageHash().'.txt';
        $GLOBALS['LOG_FILE_PATH']                          = DUPX_INIT.'/'.$GLOBALS["LOG_FILE_NAME"];
        $GLOBALS["NOTICES_FILE_NAME"]                      = "dup-installer-notices__".DUPX_Package::getPackageHash().".json";
        $GLOBALS["NOTICES_FILE_PATH"]                      = DUPX_INIT.'/'.$GLOBALS["NOTICES_FILE_NAME"];
        $GLOBALS["CHUNK_DATA_FILE_NAME"]                   = "dup-installer-chunk__".DUPX_Package::getPackageHash().".json";
        $GLOBALS["CHUNK_DATA_FILE_PATH"]                   = DUPX_INIT.'/'.$GLOBALS["CHUNK_DATA_FILE_NAME"];
        $GLOBALS['CHOWN_LOG_PATH']                         = DupProSnapLibIOU::chmod($GLOBALS['LOG_FILE_PATH'], 'u+rw');
        $GLOBALS['CHOWN_NOTICES_PATH']                     = DupProSnapLibIOU::chmod($GLOBALS['NOTICES_FILE_PATH'], 'u+rw');
        $GLOBALS['URL_SSL']                                = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on') ? true : false;
        $GLOBALS['URL_PATH']                               = ($GLOBALS['URL_SSL']) ? "https://{$_SERVER['SERVER_NAME']}{$_SERVER['REQUEST_URI']}" : "http://{$_SERVER['SERVER_NAME']}{$_SERVER['REQUEST_URI']}";
        $GLOBALS['PHP_MEMORY_LIMIT']                       = ini_get('memory_limit') === false ? 'n/a' : ini_get('memory_limit');
        $GLOBALS['PHP_SUHOSIN_ON']                         = extension_loaded('suhosin') ? 'enabled' : 'disabled';
        $GLOBALS['DISPLAY_MAX_OBJECTS_FAILED_TO_SET_PERM'] = 5;

        // Displaying notice for slow zip chunk extraction
        $GLOBALS['ZIP_ARC_CHUNK_EXTRACT_DISP_NOTICE_AFTER']                     = 5 * 60 * 60; // 5 minutes
        $GLOBALS['ZIP_ARC_CHUNK_EXTRACT_DISP_NOTICE_MIN_EXPECTED_EXTRACT_TIME'] = 10 * 60 * 60; // 10 minutes
        $GLOBALS['ZIP_ARC_CHUNK_EXTRACT_DISP_NEXT_NOTICE_INTERVAL']             = 5 * 60 * 60; // 5 minutes

        $additional_msg                           = ' for additional details <a href="https://snapcreek.com/duplicator/docs/faqs-tech/#faq-installer-015-q" target="_blank">click here</a>.';
        $GLOBALS['ZIP_ARC_CHUNK_EXTRACT_NOTICES'] = array(
            'This server looks to be under load or throttled, the extraction process may take some time',
            'This host is currently experiencing very slow I/O. You can continue to wait or try a manual extraction.',
            'This host I/O is currently having issues. It is recommended to try a manual extraction.',
        );
        foreach ($GLOBALS['ZIP_ARC_CHUNK_EXTRACT_NOTICES'] as $key => $val) {
            $GLOBALS['ZIP_ARC_CHUNK_EXTRACT_NOTICES'][$key] = $val.$additional_msg;
        }

        // init always in append before read params
        if (!is_writable(dirname($GLOBALS['LOG_FILE_PATH']))) {
            throw new Exception('Can\'t write in dup-installer folder, please check the dup-installer permission folder');
        }
        if (($GLOBALS['LOG_FILE_HANDLE'] = @fopen($GLOBALS['LOG_FILE_PATH'], "a+")) === false) {
             throw new Exception('Can\'t open the log file, please check the dup-installer permission folder');
        }

        $GLOBALS['FW_USECDN'] = false;
        // for ngrok url and Local by Flywheel Live URL
        if (isset($_SERVER['HTTP_X_ORIGINAL_HOST'])) {
            $host = $_SERVER['HTTP_X_ORIGINAL_HOST'];
        } else {
            $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME']; //WAS SERVER_NAME and caused problems on some boxes
        }
        $GLOBALS['HOST_NAME'] = $host;

        // for ngrok url and Local by Flywheel Live URL
        if (isset($_SERVER['HTTP_X_ORIGINAL_HOST'])) {
            $host = $_SERVER['HTTP_X_ORIGINAL_HOST'];
        } else {
            $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME']; //WAS SERVER_NAME and caused problems on some boxes
        }
        $GLOBALS['_CURRENT_URL_PATH'] = $host.dirname($_SERVER['PHP_SELF']);
        $GLOBALS['NOW_TIME']          = @date("His");
    }

    public static function initErrDefines()
    {
        define('ERR_CONFIG_FOUND', 'A wp-config.php already exists in this location.  This error prevents users from accidentally overwriting a WordPress site or trying to install on top of an existing one.  When the archive file is extracted it can overwrite existing items if they have the same name.  If you have already manually extracted the installer then choose #1 other-wise consider these options: <ol><li>Click &gt; Try Again &gt; Options &gt; choose "Manual Archive Extraction".</li><li>Delete the wp-config.php file and try again.</li><li>Empty the root directory except for the package and installer and try again.</li></ol>');
        define('ERR_ZIPNOTFOUND', 'The packaged zip file was not found or has become unreadable. Be sure the zip package is in the same directory as the installer file.  If you are trying to reinstall a package you can copy the package from the "'.DUPLICATOR_PRO_SSDIR_NAME.'" directory back up to your root which is the same location as your installer file.');
        define('ERR_SHELLEXEC_ZIPOPEN', 'Failed to extract the archive using shell_exec unzip');
        define('ERR_ZIPOPEN', 'Failed to open the zip archive file. Please be sure the archive is completely downloaded before running the installer. Try to extract the archive manually to make sure the file is not corrupted.');
        define('ERR_ZIPEXTRACTION', 'Errors extracting the zip file.  Portions or part of the zip archive did not extract correctly.    Try to extract the archive manually with a client side program like unzip/win-zip/winrar to make sure the file is not corrupted.  If the file extracts correctly then there is an invalid file or directory that PHP is unable to extract.  This can happen if you are moving from one operating system to another where certain naming conventions work on one environment and not another. <br/><br/> Workarounds: <br/> 1. Create a new package and be sure to exclude any directories that have name checks or files in them.   This warning will be displayed on the scan results under "Name Checks". <br/> 2. Manually extract the zip file with a client side program.  Then under options in step 1 of the installer select the "Manual Archive Extraction" option and perform the install.');
        define('ERR_ZIPMANUAL', 'When choosing "Manual Archive Extraction", the contents of the package must already be extracted for the process to continue.  Please manually extract the package into the current directory before continuing in manual extraction mode.');
        define('ERR_MAKELOG', 'PHP is having issues writing to the log file <b>'.DUPX_INIT.'\dup-installer-log__'.DUPX_Package::getPackageHash().'.txt .</b> In order for the Duplicator to proceed to validate your owner/group and permission settings for PHP on this path. Try temporarily setting you permissions to 777 to see if the issue gets resolved.  If you are on a shared hosting environment please contact your hosting company and tell them you are getting errors writing files to the path above when using PHP.');
        define('ERR_ZIPARCHIVE', 'In order to extract the archive.zip file, the PHP ZipArchive module must be installed.  Please read the FAQ for more details.  You can still install this package but you will need to select the "Manual Archive Extraction" options found under Options.  Please read the online user guide for details in performing a manual archive extraction.');
        define('ERR_MYSQLI_SUPPORT', 'In order to complete an install the mysqli extension for PHP is required. If you are on a hosted server please contact your host and request that mysqli be enabled. For more information visit: http://php.net/manual/en/mysqli.installation.php');
        define('ERR_DBCONNECT', 'DATABASE CONNECTION FAILED!<br/>');
        define('ERR_DBCONNECT_CREATE', 'DATABASE CREATION FAILURE!<br/> Unable to create database "%s". Check to make sure the user has "Create" privileges.  Some hosts will restrict the creation of a database only through the cpanel.  Try creating the database manually to proceed with the installation.  If the database already exists select the action "Connect and Remove All Data" which will remove all existing tables.');
        define('ERR_DBTRYCLEAN', 'DATABASE CREATION FAILURE!<br/> Unable to remove table from database "%s".<br/>  Please remove all tables from this database and try the installation again.  If no tables show in the database, then Drop the database and re-create it.');
        define('ERR_DBTRYRENAME', 'DATABASE CREATION FAILURE!<br/> Unable to rename a table from database "%s".<br/> Be sure the database user has RENAME privileges for this specific database on all tables.');
        define('ERR_DBCREATE', 'The database "%s" does not exist.<br/>  Change the action to create in order to "Create New Database" to create the database.  Some hosting providers do not allow database creation except through their control panels. In this case, you will need to login to your hosting providers control panel and create the database manually.  Please contact your hosting provider for further details on how to create the database.');
        define('ERR_DBEMPTY', 'The database "%s" already exists and has "%s" tables.  When using the "Create New Database" action the database should not exist.  Select the action "Connect and Remove All Data" or "Connect and Backup Any Existing Data" to remove or backup the existing tables or choose a database name that does not already exist. Some hosting providers do not allow table removal or renaming from scripts.  In this case, you will need to login to your hosting providers\' control panel and remove or rename the tables manually.  Please contact your hosting provider for further details.  Always backup all your data before proceeding!');
        define('ERR_DBMANUAL', 'The database "%s" has "%s" tables. This does not look to be a valid WordPress database.  The base WordPress install has 12 tables.  Please validate that this database is indeed pre-populated with a valid WordPress database.  The "Manual SQL execution" mode requires that you have a valid WordPress database already installed.');
        define('ERR_CPNL_API', 'The cPanel API had the following issues when trying to communicate on this host: <br/> %s');
    }
}