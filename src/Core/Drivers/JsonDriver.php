<?php declare(strict_types=1);

namespace App\Core\Drivers;

use App\Core\Driver;

final class JsonDriver implements Driver
{
    private $readTableFile;
    private $readTable;
    private $selectedIds;

    public function create(string $table, array $fields) : void
    {
        if (!is_dir($this->getDatabaseDir())) {
            mkdir($this->getDatabaseDir());
        }

        $fields['id'] = $this->getTableLastIndex($table);
        if (!$this->isTableExists($table)) {
            $tableContent = [$fields];
        } else {
            $tableContent = $this->read($table)->get();
            array_push($tableContent, $fields);
        }

        $tableFile = $this->getTableFile($table);
        file_put_contents($tableFile, json_encode($tableContent));
    }

    /**
     * Read table
     *
     * @param string $table
     * @return Driver
     * @throws \Exception
     */
    public function read(string $table) : Driver
    {
        $this->readTableFile = $tableFile = $this->getTableFile($table);
        if (!file_exists($tableFile)) {
            throw new \Exception("Table `{$table}` not found.");
        }

        $this->readTable = json_decode(file_get_contents($tableFile), true);

        return $this;
    }

    /**
     * Update table row
     *
     * @param array $fields
     * @return mixed
     * @throws \Exception
     */
    public function update(array $fields) : void
    {
        $oldResult = null;
        $tableContent = $this->readTable;

        foreach ($tableContent as $key => $row) {
            if (!in_array($row['id'], $this->selectedIds)) {
                continue;
            }

            $oldResult = $row;
            foreach ($fields as $field => $value) {
                if (!isset($tableContent[$key][$field])) {
                    throw new \Exception("Field `{$field}` not declared.");
                }
                $tableContent[$key][$field] = $value;
            }
        }

        file_put_contents($this->readTableFile, json_encode($tableContent));
    }

    /**
     * Delete table rows
     *
     * @return mixed
     * @throws \Exception
     */
    public function delete() : void
    {
        $tableFile = $this->readTableFile;
        if (null === $this->selectedIds) {
            unlink($tableFile);
            return;
        }

        $tableContent = $this->readTable;
        foreach ($tableContent as $key => $row) {
            if (!in_array($row['id'], $this->selectedIds)) {
                continue;
            }

            unset($tableContent[$key]);
        }

        file_put_contents($tableFile, json_encode($tableContent));
    }

    /**
     * Select indexes where condition true
     *
     * @param string $field
     * @param $value
     * @return mixed
     * @throws \Exception
     */
    public function where(string $field, $value) : Driver
    {
        $tableContent = $this->readTable;
        $this->selectedIds = [];
        foreach ($tableContent as $key => $row) {
            if (!isset($row[$field])) {
                throw new \Exception("Invalid table field: `{$field}`.");
            }

            if ($row[$field] === $value) {
                $this->selectedIds[] = $row['id'];
            }
        }

        return $this;
    }

    /**
     * Get result
     *
     * @return array
     * @throws \Exception
     */
    public function get() : array
    {
        if (!isset($this->readTable)) {
            throw new \Exception('Unknown table.');
        }

        if (null === $this->selectedIds) {
            return $this->readTable;
        }

        $result = [];
        foreach ($this->readTable as $key => $row) {
            if (in_array($row['id'], $this->selectedIds)) {
                $result[] = $row;
            }
        }

        return $result;
    }

    public function getDatabaseDir(string $filename = '') : string
    {
        return sprintf('%s/%s/%s', realpath('./'), 'DB', $filename);
    }

    /**
     * Check table exists
     *
     * @param string $table
     * @return bool
     */
    public function isTableExists(string $table) : bool
    {
        return file_exists($this->getTableFile($table));
    }

    /**
     * Get table file dir
     *
     * @param string $table
     * @return string
     */
    public function getTableFile(string $table) : string
    {
        return $this->getDatabaseDir("{$table}.json");
    }

    /**
     * Get last index from table
     *
     * @param string $table
     * @return int
     * @throws \Exception
     */
    public function getTableLastIndex(string $table) : int
    {
        if (!$this->isTableExists($table)) {
            return 0;
        }

        $tableContent = $this->read($table)->get();
        $lastIndex = 0;

        foreach ($tableContent as $key => $row) {
            $lastIndex = $row['id'] >= $lastIndex ? $row['id'] : $lastIndex;
        }

        return $lastIndex + 1;
    }
}