<?php declare(strict_types=1);

namespace App\Core;

class Model
{
    private $driver;
    private $fields = [];
    private $query;

    public function __construct()
    {
        $this->driver = Config::GetDatabaseDriver();
    }

    final public function where(string $field, $value) : Model
    {
        $this->query = $this->driver->read($this->table)->where($field, $value);

        return $this;
    }

    final public function delete() : void
    {
        if (isset($query)) {
            $this->query->delete();
            return;
        }

        $this->driver->read($this->table)->delete();
    }

    /**
     * Get query result
     *
     * @return array
     */
    final public function get() : array
    {
        if (isset($query)) {
            return $this->query->get();
        }

        return $this->driver->read($this->table)->get();
    }

    /**
     * Save result to DB
     *
     * @throws \Exception
     */
    final public function save() : void
    {
        foreach ($this->fillables as $field => $props) {
            if (!isset($this->fields[$field]) && !isset($props['default'])) {
                throw new \Exception("Field {$field} must be set.");
            }

            if (!isset($this->fields[$field]) && isset($props['default'])) {
                $this->fields[$field] = $props['default'];
            }
        }

        $this->driver->create($this->table, $this->fields);
    }

    public function __set($name, $value)
    {
        if (!in_array($name, array_keys($this->fillables))) {
            throw new \Exception("Field `{$name}` not declared in \$fillables.");
        }

        if (!$this->checkType($name, $value, $this->fillables)) {
            $expectedType = $this->fillables[$name]['type'];
            $receivedType = gettype($value);
            throw new \Exception("Field {$name} must be a {$expectedType}, {$receivedType} given.");
        }

        $this->fields[$name] = $value;
    }

    public static function sort(string $type, string $field, array $results) : array
    {
        usort($results, function ($a, $b) use($type, $field)
        {
            switch ($type) {
                case 'asc':
                    switch (gettype($a[$field])) {
                        case 'string':
                            return strcasecmp($a[$field], $b[$field]);
                        case 'boolean':
                            return $b[$field] ? 1 : -1;
                        case 'integer':
                            return $a[$field] > $b[$field];
                    }
                    break;
                case 'desc':
                    switch (gettype($a[$field])) {
                        case 'string':
                            return strcasecmp($b[$field], $a[$field]);
                        case 'boolean':
                            return $a[$field] - $b[$field];
                        case 'integer':
                            return $a[$field] < $b[$field];
                    }
                    break;
            }



        });

        return $results;
    }

    private function checkType(string $name, $value, array $fillables) : bool
    {
        if ($fillables[$name]['type'] === gettype($value)) {
            return true;
        }

        return false;
    }
}