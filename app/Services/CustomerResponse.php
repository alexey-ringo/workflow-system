<?php

namespace App\Services;

use App\Models\Customer;


class CustomerResponse
{
    private $error = false;
    private $customer;
    private $message;
    
    public function __construct(string $message, Customer $customer = null)
    {
        if($customer) {
            $this->error = false;
        }
        else {
            $this->error = true;
        }
        $this->customer = $customer;
        $this->message = $message;
        
    }
    
    public function hasError(): bool
    {
        return $this->error;
    }
    
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }
    
    public function getCustomerName(): string
    {
        return $this->customer ? $this->customer->name : '';
    }
    
    public function getCustomerSurname(): string
    {
        return $this->customer ? $this->customer->surname : '';
    }
    
    public function getMessage(): string 
    {
        return $this->message;
    }
}