<?php

namespace App\Http\Controllers;


use App\Traits\MessageTrait;
use App\Traits\ServiceTrait;

abstract class Controller
{
    use MessageTrait,ServiceTrait;
    public function Test(){
        $files = $graph->createRequest("GET", "/me/drive/root/children")
            ->setReturnType(Model\DriveItem::class)
            ->execute();

    }
}
