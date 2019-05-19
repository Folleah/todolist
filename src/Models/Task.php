<?php declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Task extends Model
{
    protected $table = 'tasks';

    protected $fillables = [
        'username' => ['type' => 'string',],
        'email'    => ['type' => 'string'],
        'text'     => ['type' => 'string'],
        'status'   => [
            'type' => 'boolean',
            'default' => false
        ]
    ];
}