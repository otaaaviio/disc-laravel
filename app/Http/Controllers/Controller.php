<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[
    OA\Info(version: '1.0.0', description: 'A clone of discord', title: 'Discord'),
    OA\Server(url: 'http://localhost:8000', description: 'local server'),
]
abstract class Controller
{
    //
}
