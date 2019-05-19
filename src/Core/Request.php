<?php declare(strict_types=1);

namespace App\Core;

final class Request
{
    private $path;
    private $query;
    private $method;

    public function __construct()
    {
        $currentURI = parse_url($_SERVER['REQUEST_URI']);
        $this->path = $currentURI['path'];
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->query = [];

        switch ($this->method) {
            case 'GET':
                if (isset($currentURI['query'])) {
                    parse_str($currentURI['query'], $this->query);
                }
                break;
            case 'POST':
                $this->query = $_POST;
        }
    }

    /**
     * Get URI path
     *
     * @return string
     */
    public function path() : string
    {
        return $this->path;
    }

    /**
     * Get request query
     *
     * @return array
     */
    public function query() : array
    {
        return $this->query;
    }

    /**
     * Get request method
     *
     * @return string
     */
    public function method() : string
    {
        return $this->method;
    }
}