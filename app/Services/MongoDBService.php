<?php

namespace App\Services;

use MongoDB\Client;
use MongoDB\Database;

class MongoDBService
{
    protected Database $database;
    protected string $collection;

    public function __construct()
    {
        $url = sprintf(
                'mongodb://%s:%s',
                config('mongodb.connections.mongodb.host'),
                config('mongodb.connections.mongodb.port')
            );
        $client = new Client($url);

        $this->database = $client->selectDatabase(
            config('mongodb.connections.mongodb.database')
        );
    }

    /**
     * Define a coleção a ser usada
     */
    protected function setCollection(string $collection): void
    {
        $this->collection = $collection;
    }

    /**
     * Retorna a coleção
     */
    protected function getCollection()
    {
        return $this->database->selectCollection($this->collection);
    }
}
