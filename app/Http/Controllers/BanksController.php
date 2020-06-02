<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bank;

class BanksController extends Controller
{
    /**
     * BanksController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(Bank::all());
    }
}
