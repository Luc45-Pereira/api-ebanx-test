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
        return response()->json($this->accountService->reset(), 200);
    }

    public function balance(Request $request)
    {
        $account_id = $request->query('account_id');
        $balance = $this->accountService->getBalance($account_id);

        if ($balance !== null) {
            return response()->json(['balance' => $balance], 200);
        } else {
            return response()->json(['error' => 'Account not found'], 404);
        }
    }
}
