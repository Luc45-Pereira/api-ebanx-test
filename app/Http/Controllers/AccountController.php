<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AccountService;

class AccountController extends Controller
{
    private $accountService;

    public function __construct()
    {
        $this->accountService = AccountService::getInstance();
    }

    public function reset()
    {
        $this->accountService->reset();
        return response('OK', 200);

    }

    public function balance(Request $request)
    {
        $account_id = $request->query('account_id');
        $balance = $this->accountService->getBalance($account_id);

        if ($balance !== null) {
            return response()->json($balance, 200);
        } else {
            return response()->json(0, 404);
        }
    }
}
