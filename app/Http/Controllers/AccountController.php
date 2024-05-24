<?php

namespace App\Http\Controllers;

use App\Http\Traits\AccountsTrait;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    use AccountsTrait;

    public function reset()
    {
        $this->accounts = [];
        session(['accounts' => $this->accounts]);
        return response()->json(['status' => 'OK'], 200);
    }

    public function balance(Request $request)
    {
        $account_id = $request->query('account_id');

        if (isset($this->accounts[$account_id])) {
            return response()->json(['balance' => $this->accounts[$account_id]['balance']], 200);
        } else {
            return response()->json(['error' => 'Account not found'], 404);
        }
    }
}
