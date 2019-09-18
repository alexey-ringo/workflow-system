<?php

namespace App\Services;

use App\Models\Contract;


class ContractResponse
{
    private $error = false;
    private $contract;
    private $message;
    
    public function __construct(string $message, Contract $contract = null)
    {
        if($contract) {
            $this->error = false;
        }
        else {
            $this->error = true;
        }
        $this->contract = $contract;
        $this->message = $message;
        
    }
    
    public function hasError(): bool
    {
        return $this->error;
    }
    
    public function getContract(): ?Contract
    {
        return $this->contract;
    }
    
    public function getContractId(): int
    {
        return $this->contract ? $this->contract->id : 0;
    }
    
    public function getContractNum(): int
    {
        return $this->contract ? $this->contract->contract_num : 0;
    }
    
    public function getMessage(): string 
    {
        return $this->message;
    }
}