<?php

namespace App\Repositories\BaseRepository;

interface RepositoryInterface
{
    public static function __callStatic($method, $arguments);

    public function __call($method, $arguments);
}
