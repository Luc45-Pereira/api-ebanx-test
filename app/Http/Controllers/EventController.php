<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AccountService;

class EventController extends Controller
{
    private $accountService;

    public function __construct()
    {
        $this->accountService = AccountService::getInstance();
    }

    public function handleEvent(Request $request)
    {
        $type = $request->input('type');
        $destination = $request->input('destination');
        $amount = $request->input('amount');
        $origin = $request->input('origin');

        if ($type === 'deposit') {
            $account = $this->accountService->deposit($destination, $amount);
            return response()->json(['destination' => $account], 201);
        } elseif ($type === 'transfer') {
            $result = $this->accountService->transfer($origin, $destination, $amount);

            if ($result) {
                return response()->json($result, 200);
            } else {
                return response()->json(['error' => 'Account not found'], 404);
            }
        } elseif ($type === 'withdraw') {
            $account = $this->accountService->withdraw($origin, $amount);

            if ($account) {
                return response()->json(['origin' => $account], 200);
            } else {
                return response()->json(['error' => 'Account not found'], 404);
            }
        } else {
            return response()->json(['error' => 'Invalid event type'], 400);
        }
    }
}
