<?php

namespace Contentify\Vendor;

use PDO;

/**
* Database MySQLDump Class File
* Copyright (c) 2009 by James Elliott - James.d.Elliott@gmail.com
* Modified by Contentify.org
* GNU General Public License v3 http://www.gnu.org/licenses/gpl.html
*/
class MySqlDump
{

    /**
     * DB server
     *
     * @var string
     */
    public $host;

    /**
     * DB username
     *
     * @var string
     */
    public $user;

    /**
     * DB user password
     *
     * @var string
     */
    public $pass;

    /**
     * DB name
     *
     * @var string
     */
    public $db;

    /**
     * Name of the dump file
     *
     * @var string
     */
    public $filename = 'dump.sql';

    /**
     * Usable switch
     *
     * @var bool
     */
    public $dropTableIfExists = false;

    /**
     * Compress the file?
     *
     * @var bool
     */
    public $compress = false;

    /**
     * Array with table names
     *
     * @var string[]
     */
    private $tables = array();

    /**
     * @var PDO
     */
    private $db_handler;

    /**
     * @var resource
     */
    private $file_handler;

    /**
     * Constructor of MySQLDump
     *
     * @param string $db   Database name
     * @param string $user MySQL account username
     * @param string $pass MySQL account password
     * @param string $host MySQL server to connect to
     */
    public function __construct($db = '', $user = '', $pass = '', $host = 'localhost')
    {
        $this->db   = $db;
        $this->user = $user;
        $this->pass = $pass;
        $this->host = $host;
    }

    /**
     * Main call
     *
     * @param string $filename Name of file to write sql dump to
     * @return bool
     * @throws \Exception
     */
    public function start($filename = '')
    {
        // Output file can be redefined here
        if (!empty($filename))
        {
            $this->filename = $filename;
        }

        // We must set a name to continue
        if (empty($this->filename))
        {
            throw new \Exception("Output file name is not set", 1);
        }

        // Check for zlib
        if ((true === $this->compress) && !function_exists("gzopen"))
        {
            throw new \Exception("Compression is enabled, but zlib is not installed or configured properly", 1);
        }

        // Trying to bind a file with block
        if (true === $this->compress)
        {
            $this->file_handler = gzopen($this->filename, "wb");
        } else {
            $this->file_handler = fopen($this->filename, "wb");
        }
        if (false === $this->file_handler)
        {
            throw new \Exception("Output file is not writable", 2);
        }

        // Connecting with MySQL
        try
        {
            $this->db_handler = new \PDO("mysql:dbname={$this->db};host={$this->host}", $this->user, $this->pass);
        } catch (\PDOException $e) {
            throw new \Exception("Connection to MySQL failed with message: " . $e->getMessage(), 3);
        }

        // Fix for always-unicode output
        $this->db_handler->exec("SET NAMES utf8");
        // https://github.com/clouddueling/mysqldump-php/issues/9
        $this->db_handler->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_NATURAL);

        // Formatting dump file
        $this->writeHeader();

        // Listing all tables from database
        $this->tables = array();
        foreach ($this->db_handler->query("SHOW TABLES") as $row) {
            array_push($this->tables, current($row));
        }

        // Exporting tables one by one
        foreach ($this->tables as $table)
        {
            $this->write("-- --------------------------------------------------------\n\n");
            $this->getTableStructure($table);
            $this->listValues($table);
        }

        // Releasing file
        if (true === $this->compress)
        {
            return gzclose($this->file_handler);
        }

        return fclose($this->file_handler);
    }

    /**
     * Output routine
     *
     * @param string $string SQL to write to dump file
     * @throws \Exception
     */
    private function write($string)
    {
        if (true === $this->compress)
        {
            if (false === gzwrite($this->file_handler, $string))
            {
                throw new \Exception("Writing to file failed! Probably, there is no more free space left?", 4);
            }
        } else {
            if (false === fwrite($this->file_handler, $string))
            {
                throw new \Exception("Writing to file failed! Probably, there is no more free space left?", 4);
            }
        }
    }

    /**
     * Writing header for dump file
     *
     * @return void
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
     * @param string $tableName Name of the table to export
     * @return void
     */
    private function getTableStructure($tableName)
    {
        $this->write("--\n-- Table structure for table `$tableName`\n--\n\n");

        if (true === $this->dropTableIfExists)
        {
            $this->write("DROP TABLE IF EXISTS `$tableName`;\n\n");
        }

        foreach ($this->db_handler->query("SHOW CREATE TABLE `$tableName`") as $row)
        {
            $this->write($row['Create Table'] . ";\n\n");
        }
    }

    /**
     * Table rows extractor
     *
     * @param string $tableName Name of the table to export
     * @return void
     */
    private function listValues($tableName)
    {
        $this->write("--\n-- Dumping data for table `$tableName`\n--\n\n");

        foreach ($this->db_handler->query("SELECT * FROM `$tableName`", PDO::FETCH_NUM) as $row)
        {
            $values = array();
            foreach ($row as $value)
            {
                $values[] = is_null($value) ? "NULL" : $this->db_handler->quote($value);
            }
            $this->write("INSERT INTO `$tableName` VALUES(" . implode(", ", $values) . ");\n");
        }

        $this->write("\n");
    }
}