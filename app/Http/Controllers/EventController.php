<?php

namespace App\Http\Controllers;

use App\Http\Traits\AccountsTrait;
use Illuminate\Http\Request;

class EventController extends Controller
{
    use AccountsTrait;
    public function handleEvent(Request $request)
    {
        $type = $request->input('type');
        $destination = $request->input('destination');
        $amount = $request->input('amount');
        $origin = $request->input('origin');

        if ($type === 'deposit') {
            if (isset($this->accounts[$destination])) {
                $this->accounts[$destination]['balance'] += $amount;
            } else {
                $this->accounts[$destination] = ['id' => $destination, 'balance' => $amount];
            }

            return response()->json(['destination' => ['id' => $destination, 'balance' => $this->accounts[$destination]['balance']]], 201);
        } elseif ($type === 'transfer') {
            if (isset($this->accounts[$origin]) && isset($this->accounts[$destination])) {
                $this->accounts[$origin]['balance'] -= $amount;
                $this->accounts[$destination]['balance'] += $amount;

                return response()->json([
                    'origin' => ['id' => $origin, 'balance' => $this->accounts[$origin]['balance']],
                    'destination' => ['id' => $destination, 'balance' => $this->accounts[$destination]['balance']]
                ], 200);
            } else {
                return response()->json(['error' => 'Account not found'], 404);
            }
        } elseif ($type === 'withdraw') {
            if (isset($this->accounts[$origin])) {
                $this->accounts[$origin]['balance'] -= $amount;

                return response()->json(['origin' => ['id' => $origin, 'balance' => $this->accounts[$origin]['balance']]], 200);
            } else {
                return response()->json(['error' => 'Account not found'], 404);
            }
        } else {
            return response()->json(['error' => 'Invalid event type'], 400);
        }
    }
}
