<?php

namespace App\Http\Controllers;


use App\Traits\FilterTrait;
use App\Traits\MessageTrait;
use App\Traits\ServiceTrait;
use Spatie\Permission\Traits\HasRoles;

abstract class Controller
{
    use MessageTrait,ServiceTrait,FilterTrait;
    public function Test(){
        $files = $graph->createRequest("GET", "/me/drive/root/children")
            ->setReturnType(Model\DriveItem::class)
            ->execute();

    }
}
