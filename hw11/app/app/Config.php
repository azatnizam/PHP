<?php
namespace App;
class Config
{
    const CONFIG_FILE = '../config.ini';
    private $config = [];

    public function __construct()
    {
        $config = parse_ini_file(static::CONFIG_FILE);

        if (!$config) {
            throw new \Exception('wrong config file');
        }

        $this->config = $config;

    }

    public function __get($key)
    {
        if (!isset($this->config[$key])) {
            throw new \Exception('Wrong config key');
        }

        return $this->config[$key];
    }
}
