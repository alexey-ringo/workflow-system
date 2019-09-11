<?php

namespace App\Services;

use App\Models\Contract;


class ContractResponse
{
    private $hasError = false;
    private $contract;
    private $message;
    
    public function __construct(string $message, Contract $contract = null)
    {
        if($contract) {
            $this->hasError = false;
        }
        else {
            $this->hasError = true;
        }
        $this->contract = $contract;
        $this->message = $message;
        
    }
    
    public function getError(): bool
    {
        return $this->hasError;
    }
    
    public function getContract(): ?Contract
    {
        return $this->contract;
    }
    
    public function getContractId(): ?int
    {
        return $this->contract ? $this->contract->id : null;
    }
    
    public function getContractNum(): ?int
    {
        return $this->contract ? $this->contract->contract_num : null;
    }
    
    public function getMessage(): string 
    {
        return $this->message;
    }
}