<?php


namespace AYakovlev\Controller;

class DefaultController
{
    public function index()
    {
        header('Location: /event/compare');
    }
}