<?php

namespace App\Services;

use App\Models\Contract;


class ContractResponse
{
    private $contract;
    private $message;
    
    public function __construct(string $message, Contract $contract)
    {
        $this->contract = $contract;
        $this->message = $message;
        
    }
    
    
    public function getContract(): Contract
    {
        return $this->contract;
    }
    
    public function getContractId(): int
    {
        return $this->contract->id;
    }
    
    public function getContractNum(): int
    {
        return $this->contract->contract_num;
    }
    
    public function getMessage(): string 
    {
        return $this->message;
    }
}