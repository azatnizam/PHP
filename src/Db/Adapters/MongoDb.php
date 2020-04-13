<?php

namespace Bjlag\Db\Adapters;

use Bjlag\Db\Store;
use MongoDB\BSON\ObjectId;
use MongoDB\InsertOneResult;
use MongoDB\Model\BSONArray;
use MongoDB\Model\BSONDocument;
use MongoDB\UpdateResult;

class MongoDb implements Store
{
    /** @var \MongoDB\Client */
    private $client;

    /** @var string */
    private $dbname;

    /**
     * mongodb://[username:password@]host1[:port1][,host2[:port2],...[,hostN[:portN]]][/[database][?options]]
     *
     * @param string $uri
     * @param string $dbname
     * @return \Bjlag\Db\Adapters\MongoDb
     */
    public function getConnection(string $uri, string $dbname): Store
    {
        $this->client = new \MongoDB\Client($uri);
        $this->dbname = $dbname;

        return $this;
    }

    public function find(string $from, array $select = [], array $where = [], ?int $limit = null, ?int $offset = null): array
    {
        $options = [];
        $columns = [];
        foreach ($select as $column) {
            $columns[$column] = 1;
        }

        if (count($columns) > 0){
            $options['projection'] = $columns;
        }

        $result = [];
        $collection = $this->client->selectCollection($this->dbname, $from);
        $rows = $collection->find($where, $options)->toArray();

        foreach ($rows as $row) {
            $data = [];
            foreach ($row as $key => $field) {
                if ($field instanceof ObjectId) {
                    $field = $field->jsonSerialize();
                    $data['id'] = $field['$oid'];
                } elseif ($field instanceof BSONArray) {
                    $data[$key] = $this->extractFields($field);
                } else {
                    $data[$key] = $field;
                }
            }

            $result[] = $data;
        }

        return $result;
    }

    /**
     * @param \MongoDB\Model\BSONArray $value
     * @return array
     */
    private function extractFields(BSONArray $value): array
    {
        $result = [];

        $items = $value->jsonSerialize();
        foreach ($items as $key => $item) {
            if ($item instanceof BSONArray) {
                $result[$key] = $this->extractFields($item);
            } else {
                $result[$key] = $item instanceof BSONDocument ? (array) $item->jsonSerialize() : $item;
            }
        }

        return $result;
    }

    /**
     * @param string $to
     * @param array $data
     * @return mixed
     */
    public function add(string $to, array $data)
    {
        $collection = $this->client->selectCollection($this->dbname, $to);
        $result = $collection->insertOne($data);

        return $result instanceof InsertOneResult ? $result->getInsertedId()->jsonSerialize()['$oid'] : $result;
    }

    /**
     * @param string $table
     * @param array $where
     * @param array $data
     *
     * @return int
     */
    public function update(string $table, array $where, array $data): int
    {
        if (key_exists('id', $where)) {
            $where['_id'] = new ObjectId($where['id']);
            unset($where['id']);
        }

        $collection = $this->client->selectCollection($this->dbname, $table);
        $result = $collection->updateOne($where, ['$set' => $data]);

        return $result->getModifiedCount();
    }

    public function delete()
    {
        // TODO: Implement delete() method.
    }
}
