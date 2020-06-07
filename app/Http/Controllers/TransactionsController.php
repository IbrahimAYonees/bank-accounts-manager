<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionsCollection;
use Illuminate\Http\Request as StandardRequest;
use App\Transaction;
use App\Account;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class TransactionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(StandardRequest $request)
    {
        $v = Validator::make($request->all(),[
            'operations' => [
                'nullable',
                'string',
                Rule::in(['deposits', 'withdraws','transfers'])
            ],
            'account_id' => 'nullable|integer|min:1',
            'bank_id' => 'nullable|integer|min:1',
            'account_number' => 'nullable|string',
            'from' => 'nullable|date',
            'to' => [
                Rule::requiredIf($request['from']),
                'after:from'
            ]
        ]);

        if($v->fails()){
            $fields = array_keys($v->errors()->toArray());
            foreach ($fields as $field) {
                unset($request[$field]);
            }
        }

        return new TransactionsCollection(
            Transaction::query()
                ->with(['account','account.bank','account.currency'])
                ->operationsFilter($request['operations'])
                ->accountFilter($request['account_id'])
                ->bankFilter($request['bank_id'])
                ->accountNumberFilter($request['account_number'])
                ->dateFilter($request['from'],$request['to'])
                ->latest()
                ->paginate(10)
        );
    }

    /**
     * @param Account $account
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Account $account)
    {
        $transaction = new Transaction();
        $transaction->account_id = $account->id;
        $transaction->save();

        return response()->json([
            'transaction_id' => $transaction->id
        ]);
    }
}
