<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function Test(){
        $files = $graph->createRequest("GET", "/me/drive/root/children")
            ->setReturnType(Model\DriveItem::class)
            ->execute();

    }
}
