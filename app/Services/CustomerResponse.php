<?php

namespace App\Services;

use App\Models\Customer;


class CustomerResponse
{
    private $hasError = false;
    private $customer;
    private $message;
    
    public function __construct(string $message, Customer $customer = null)
    {
        if($customer) {
            $this->hasError = false;
        }
        else {
            $this->hasError = true;
        }
        $this->customer = $customer;
        $this->message = $message;
        
    }
    
    public function getError(): bool
    {
        return $this->hasError;
    }
    
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }
    
    public function getCustomerName(): ?string
    {
        return $this->customer ? $this->customer->name : null;
    }
    
    public function getCustomerSurname(): ?string
    {
        return $this->customer ? $this->customer->surname : null;
    }
    
    public function getMessage(): string 
    {
        return $this->message;
    }
}