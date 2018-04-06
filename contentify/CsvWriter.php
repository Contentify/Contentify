<?php

namespace Contentify;

use Cache;
use RuntimeException;
use View;

/**
 * This class creates a CSV text from records.
 * The conversion is smart and will enclosure values and escape them if necessary.
 *
 * Note: This writer is not optimized for high performance when writing.
 *
 * Note: The methods of this class are similar to those of \League\Csv\Writer
 * @see https://github.com/thephpleague/csv
 */
class CsvWriter
{

    /**
     * Array (that represents rows) of arrays (that represent the column values of a row)
     * @var mixed[][]
     */
    protected $records = [];

    /**
     * String that separates the column values of a row (line) - one character!
     *
     * @var string
     */
    protected $delimiter = ';';

    /**
     * String that enclosures column values - one character!
     *
     * @var string
     */
    protected $enclosure = '"';

    /**
     * String that escapes characters - one character!
     *
     * @var string
     */
    protected $escape = '\\';

    /**
     * String that separates/ends lines - cannot be empty!
     *
     * @var string
     */
    protected $newline = "\n";

    /**
     * Class constructor.
     *
     * @param mixed[][] $records The records - may include a header row
     */
    public function __construct(array $records = [])
    {
        if (sizeof($records) > 0) {
            $this->insertAll($records);
        }
    }

    /**
     * @param mixed[][] $records
     */
    public function setAll(array $records)
    {
        $this->records = $records;
    }

    /**
     * Inserts multiple records - for example an array of records
     *
     * @param \Traversable|array $records
     * @return int
     */
    public function insertAll($records)
    {
        if (! is_iterable($records)) {
            throw new \InvalidArgumentException('The $records argument has to be iterable (for example, arrays are)');
        }

        $bytes = 0;
        foreach ($records as $record) {
            $bytes += $this->insertOne($record);
        }

        return $bytes;
    }

    /**
     * Inserts a single record.
     * You may to use this method as well if you want to add a header.
     *
     * @param mixed[] $record
     * @return int
     */
    public function insertOne(array $record)
    {
        $bytes = $this->validateRecord($record);
        $this->records[] = $record;

        return $bytes;
    }

    /**
     * Validates a single record. Throws an exception if validation fails.
     * Returns the number of bytes otherwise.
     *
     * @param mixed[] $record
     * @return int
     * @throws \Exception
     */
    public function validateRecord(array $record)
    {
        if (sizeof($this->records) > 0 and sizeof($record) != sizeof(current($this->records))) {
            throw new \InvalidArgumentException('The number of the column values of the record has to be the same as'
                .' the number of column values of the other records ('.sizeof(current($this->records)));
        }
        if (sizeof($record) === 0) {
            throw new \InvalidArgumentException('The record has to have at least one column value');
        }

        $bytes = 0;
        foreach ($record as $index => $columnValue) {
            try {
                $bytes += mb_strlen($columnValue);
            } catch (\Exception $exception) {
                throw new \Exception('The '.($index + 1).'. column value of the tested record is invalid - '.
                    ' converting to string failed');
            }
        }

        return $bytes;
    }

    /**
     * @return string
     */
    public function getDelimiter()
    {
        return $this->delimiter;
    }

    /**
     * @param string $delimiter
     * @return $this
     */
    public function setDelimiter($delimiter)
    {
        if (mb_strlen($delimiter) !== 1) {
            throw new \InvalidArgumentException('The length of the delimiter string has to be one');
        }
        $this->delimiter = $delimiter;

        return $this;
    }

    /**
     * @return string
     */
    public function getEnclosure()
    {
        return $this->enclosure;
    }

    /**
     * @param string $enclosure
     * @return $this
     */
    public function setEnclosure($enclosure)
    {
        if (mb_strlen($enclosure)  !== 1) {
            throw new \InvalidArgumentException('The length of the enclosure string has to be one');
        }
        $this->enclosure = $enclosure;

        return $this;
    }

    /**
     * @return string
     */
    public function getNewline()
    {
        return $this->newline;
    }

    /**
     * @param string $newline
     * @return $this
     */
    public function setNewline($newline)
    {
        if (mb_strlen($newline) < 1) {
            throw new \InvalidArgumentException('The length of the newline string has to be greater 0');
        }
        $this->newline = $newline;

        return $this;
    }

    /**
     * Deletes all records (including the header)
     *
     * @return $this
     */
    public function clearAll()
    {
        $this->records = [];

        return $this;
    }

    /**
     * Returns the number of records (rows).
     * Note: If there is a header column it will be count as well.
     *
     * @return int
     */
    public function countAll()
    {
        return sizeof($this->records);
    }

    /**
     * Returns the content as a string.
     *
     * @return string
     */
    public function getContent()
    {
        $content = '';

        foreach ($this->records as $record) {
            $content .= $this->recordToString($record);
        }

        return $content;
    }

    /**
     * Converts a record to a string and returns this string
     *
     * @param mixed[] $record
     * @return string
     */
    protected function recordToString(array $record)
    {
        $content = '';

        foreach ($record as $columnValue) {
            if ($content !== '') {
                $content .= $this->delimiter;
            }

            // If the column value contains the enclosure string and if this enclosure string is not escaped,
            // we have to escape it by doubling it.
            $containsEnclosure = (mb_strpos($columnValue, $this->enclosure) !== false);
            if ($containsEnclosure) {
                $regexEscape = '\\';
                $columnValue = preg_replace('/([^'.$regexEscape.$this->escape.'])(")/', '$1"$2', $columnValue);
            }

            // Wrap the column value in the enclosure string if the column value contains the delimiter string
            if (mb_strpos($columnValue, $this->delimiter) !== false or $containsEnclosure) {
                $columnValue = $this->enclosure.$columnValue.$this->enclosure;
            }

            $content .= $columnValue;
        }

        return $content.$this->newline;
    }

}