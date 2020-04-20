<?php

namespace App\Action;

use App\App;
use App\Domain\Event;
use App\Domain\EventMapper;
use App\Responce;
use JsonException;

class EventCreate
{
    public static function action(): Responce
    {
        $query = null;
        try {
            $query = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR | JSON_OBJECT_AS_ARRAY);
        } catch (JsonException $e) {
        }

        if (
            !is_array($query)
            || !isset($query['priority'], $query['conditions'], $query['event'])
            || !is_int($query['priority'])
            || !is_array($query['conditions'])
            || !is_array($query['event'])
        ) {
            return Responce::error(400);
        }

        // Согласно ТЗ: "Для простоты все критерии заданы так: <критерий>=<значение>"
        foreach ($query['conditions'] as $key => $cond) {
            if (!is_string($key) || !is_scalar($cond)) {
                return Responce::error(400);
            }
        }

        $event = new Event($query['priority'], $query['conditions'], $query['event']);
        $mapper = new EventMapper(App::getRedis());
        $mapper->save($event);

        return new Responce($event->id, 201);
    }
}