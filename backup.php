<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "39";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "seguridad.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php


/*
* Database MySQLDump Class File
* Copyright (c) 2009 by James Elliott
* James.d.Elliott@gmail.com
* GNU General Public License v3 http://www.gnu.org/licenses/gpl.html
*
*/

class MySQLDump
{

    // This can be set both on constructor or manually
    public $host;
    public $user;
    public $pass;
    public $db;
    public $filename = 'dump.sql';

    // Usable switch
    public $nodata = false;
    public $droptableifexists = false;
    public $include = array();
    public $exclude = array();

    //compress
    public $compress = false;

    // Internal stuff
    private $tables = array();
    private $views = array();
    private $db_handler;
    private $file_handler;

    /**
     * Constructor of MySQLDump
     *
     * @param string $db        Database name
     * @param string $user      MySQL account username
     * @param string $pass      MySQL account password
     * @param string $host      MySQL server to connect to
     * @return null
     */
    public function __construct($db = 'liosarpc_pangloria', $user = 'liosarpc', $pass = 'proview2010$', $host = 'sql.byethost16.org')
    {
        $this->db = $db;
        $this->user = $user;
        $this->pass = $pass;
        $this->host = $host;
    }

    /**
     * Main call
     *
     * @param string $filename  Name of file to write sql dump to
     * @return bool
     */
    public function start($filename = '')
    {
        // Output file can be redefined here
        if (!empty($filename)) {
            $this->filename = $filename;
        }
        // We must set a name to continue
        if (empty($this->filename)) {
            throw new \Exception("Output file name is not set", 1);
        }
        // Check for zlib
        if ((true === $this->compress) && !function_exists("gzopen")) {
            throw new \Exception("Compression is enabled, but zlib is not installed or configured properly", 1);
        }
        // Trying to bind a file with block
        if (true === $this->compress) {
            $this->file_handler = gzopen($this->filename, "wb");
        } else {
            $this->file_handler = fopen($this->filename, "wb");
        }
        if (false === $this->file_handler) {
            throw new \Exception("Output file is not writable", 2);
        }
        // Connecting with MySQL
        try {
            $this->db_handler = new \PDO("mysql:dbname={$this->db};host={$this->host}", $this->user, $this->pass);
        } catch (\PDOException $e) {
            throw new \Exception("Connection to MySQL failed with message: " . $e->getMessage(), 3);
        }
        // Fix for always-unicode output
        $this->db_handler->exec("SET NAMES utf8");
        // https://github.com/clouddueling/mysqldump-php/issues/9
        $this->db_handler->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_NATURAL);
        // Formating dump file
        $this->writeHeader();
        // Listing all tables from database
        $this->tables = array();
        foreach ($this->db_handler->query("SHOW TABLES") as $row) {
            if (empty($this->include) || (!empty($this->include) && in_array(current($row), $this->include, true))) {
                array_push($this->tables, current($row));
            }
        }
        // Exporting tables one by one
        foreach ($this->tables as $table) {
            if (in_array($table, $this->exclude, true)) {
                continue;
            }
            $is_table = $this->getTableStructure($table);
            if (true === $is_table && false === $this->nodata) {
                $this->listValues($table);
            }
        }
        foreach ($this->views as $view) {
            $this->write($view);
        }
        // Releasing file
        if (true === $this->compress) {
            return gzclose($this->file_handler);
        }

        return fclose($this->file_handler);
    }

    /**
     * Output routine
     *
     * @param string $string  SQL to write to dump file
     * @return bool
     */
    private function write($string)
    {
        if (true === $this->compress) {
            if (false === gzwrite($this->file_handler, $string)) {
                throw new \Exception("Writting to file failed! Probably, there is no more free space left?", 4);
            }
        } else {
            if (false === fwrite($this->file_handler, $string)) {
                throw new \Exception("Writting to file failed! Probably, there is no more free space left?", 4);
            }
        }
    }

    /**
     * Writting header for dump file
     *
     * @return null
     */
    private function writeHeader()
    {
        // Some info about software, source and time
        $this->write("-- mysqldump-php SQL Dump\n");
        $this->write("-- https://github.com/clouddueling/mysqldump-php\n");
        $this->write("--\n");
        $this->write("-- Host: {$this->host}\n");
        $this->write("-- Generation Time: " . date('r') . "\n\n");
        $this->write("--\n");
        $this->write("-- Database: `{$this->db}`\n");
        $this->write("--\n\n");
    }

    /**
     * Table structure extractor
     *
     * @param string $tablename  Name of table to export
     * @return null
     */
    private function getTableStructure($tablename)
    {
        foreach ($this->db_handler->query("SHOW CREATE TABLE `$tablename`") as $row) {
            if (isset($row['Create Table'])) {
                $this->write("-- --------------------------------------------------------\n\n");
                $this->write("--\n-- Table structure for table `$tablename`\n--\n\n");
                if (true === $this->droptableifexists) {
                    $this->write("DROP TABLE IF EXISTS `$tablename`;\n\n");
                }
                $this->write($row['Create Table'] . ";\n\n");
                return true;
            }
            if (isset($row['Create View'])) {
                $view  = "-- --------------------------------------------------------\n\n";
                $view .= "--\n-- Table structure for view `$tablename`\n--\n\n";
                $view .= $row['Create View'] . ";\n\n";
                $this->views[] = $view;
                return false;
            }
        }
    }

    /**
     * Table rows extractor
     *
     * @param string $tablename  Name of table to export
     * @return null
     */
    private function listValues($tablename)
    {
        $this->write("--\n-- Dumping data for table `$tablename`\n--\n\n");
        foreach ($this->db_handler->query("SELECT * FROM `$tablename`", PDO::FETCH_NUM) as $row) {
            $vals = array();
            foreach ($row as $val) {
                $vals[] = is_null($val) ? "NULL" : $this->db_handler->quote($val);
            }
            $this->write("INSERT INTO `$tablename` VALUES(" . implode(", ", $vals) . ");\n");
        }
        $this->write("\n");
    }
}