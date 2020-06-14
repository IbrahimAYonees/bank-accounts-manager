<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bank;
use App\Http\Resources\BankResource;

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
    public function index(Request $request)
    {
        $banks = $request['withAccounts'] ?
            BankResource::collection(Bank::query()->with('accounts')->get()) :
            Bank::all();
        return response()->json($banks);
    }
}
