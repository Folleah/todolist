<?php declare(strict_types=1);

namespace App\Core;

interface Driver
{
    /**
     * Create new row
     *
     * @param $table
     * @param $fields
     * @return mixed
     */
    public function create(string $table, array $fields);

    /**
     * Read table
     *
     * @param $table
     * @return Driver
     */
    public function read(string $table) : Driver;

    /**
     * Update table result
     *
     * @param array $fields
     * @return mixed
     */
    public function update(array $fields);

    /**
     * Delete table result
     *
     * @return mixed
     */
    public function delete();

    /**
     * Select indexes where condition true
     *
     * @param string $field
     * @param $value
     * @return mixed
     */
    public function where(string $field, $value) : Driver;

    /**
     * Get result
     *
     * @return array
     */
    public function get() : array;
}