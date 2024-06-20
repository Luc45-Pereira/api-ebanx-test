<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class AccountService
{
    private static $instance = null;
    private $cacheKey = 'accounts';

    private $accounts;

    const LIMIT = -200;

    private function __construct()
    {
        if (Cache::has($this->cacheKey)) {
            $this->accounts = Cache::get($this->cacheKey);
        } else {
            $this->accounts = [300 => ['id' => 300, 'balance' => 0]];
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __destruct()
    {
        Cache::put($this->cacheKey, $this->accounts, 3600); // Cache por 1 hora
    }

    public function reset()
    {
        $this->accounts = [300 => ['id' => "300", 'balance' => 0]];
        Cache::put($this->cacheKey, $this->accounts, 3600);
        return 'OK';
    }

    public function getBalance($account_id)
    {
        return $this->accounts[$account_id]['balance'] ?? null;
    }

    public function deposit($account_id, $amount)
    {
        if (isset($this->accounts[$account_id])) {
            $this->accounts[$account_id]['balance'] += $amount;
        } else {
            $this->accounts[$account_id] = ['id' => $account_id, 'balance' => $amount];
        }

        Cache::put($this->cacheKey, $this->accounts, 3600); // Atualizar cache

        return $this->accounts[$account_id];
    }

    public function transfer($origin, $destination, $amount)
    {
        if (isset($this->accounts[$origin]) && $this->verifyLimit($origin, $amount)) {
            $this->accounts[$origin]['balance'] -= $amount;
            $this->accounts[$destination]['balance'] += $amount;

            Cache::put($this->cacheKey, $this->accounts, 3600); // Atualizar cache

            return [
                'origin' => $this->accounts[$origin],
                'destination' => $this->accounts[$destination],
            ];
        }

        return null;
    }

    public function withdraw($account_id, $amount)
    {
        if (isset($this->accounts[$account_id]) && $this->verifyLimit($account_id, $amount)) {
            $this->accounts[$account_id]['balance'] -= $amount;

            Cache::put($this->cacheKey, $this->accounts, 3600); // Atualizar cache

            return $this->accounts[$account_id];
        }

        return null;
    }

    public function verifyLimit($account_id, $amount)
    {
        return ($this->accounts[$account_id]['balance'] - $amount) > self::LIMIT;
    }

}
