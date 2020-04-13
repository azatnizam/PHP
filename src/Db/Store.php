<?php

namespace Bjlag\Db;

interface Store
{
    public function getConnection(string $uri, string $dbname): self;

    public function find(string $from, array $select = [], array $where = [], ?int $limit = null, ?int $offset = null): array;

    public function add(string $to, array $data);

    public function update();

    public function delete();
}
