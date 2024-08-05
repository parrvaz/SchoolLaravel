<?php

namespace App\Http\Controllers;


use App\Traits\MessageTrait;

abstract class Controller
{
    use MessageTrait;
    public function Test(){
        $files = $graph->createRequest("GET", "/me/drive/root/children")
            ->setReturnType(Model\DriveItem::class)
            ->execute();

    }
}
