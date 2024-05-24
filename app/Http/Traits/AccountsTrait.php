<?php

namespace App\Http\Traits;

trait AccountsTrait
{
    private $accounts = [];

    public function __construct()
    {
        if (session()->has('accounts')) {
            $this->accounts = session('accounts');
        }
    }

    public function __destruct()
    {
        session(['accounts' => $this->accounts]);
    }
}