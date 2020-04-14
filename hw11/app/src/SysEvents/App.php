<?php


namespace SysEvents;


class App
{

    public function run()
    {
        $this->addTestEvents();
        $this->selectTest();
    }

    public function addTestEvents()
    {
        System::inst()->addEvent(Event::create(1000,   ['id' => 1], ['param1'=> 1, 'param2'=> 2]));
        System::inst()->addEvent(Event::create(400,    ['id' => 2], ['param1'=> 2, 'param2'=> 1]));
        System::inst()->addEvent(Event::create(1000,   ['id' => 3], ['param1'=> 1]));
        System::inst()->addEvent(Event::create(100,    ['id' => 4], ['param1'=> 1, 'param2'=> 2]));
    }

    public function selectTest($cond = ['param1'=> 1, 'param2'=> 2])
    {
        $e = System::inst()->selectEvent($cond);
        echo "<pre>";
        if ($e) {
            echo "SUCCESS: ";
            print_r($e->toArray());
        } else {
            echo "NOT EXISTS";
        }

        echo "</pre>";
    }

}