<?php

namespace App\Http\Controllers;

use App\Http\Resources\ConnectionResourceCollection;
use App\Models\Connection;

class ConnectionController extends Controller
{
    public function list(): ConnectionResourceCollection
    {
        $connections = Connection::all();

        return new ConnectionResourceCollection($connections);
    }
}
