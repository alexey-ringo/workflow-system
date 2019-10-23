<?php

namespace App\Services;

use App\Models\Customer;


class CustomerResponse
{
    private $customer;
    private $message;
    
    public function __construct(string $message, Customer $customer)
    {
        $this->customer = $customer;
        $this->message = $message;
        
    }
    
    public function getCustomer(): Customer
    {
        return $this->customer;
    }
    
    public function getCustomerName(): string
    {
        return $this->customer->name;
    }
    
    public function getCustomerSurname(): string
    {
        return $this->customer->surname;
    }
    
    public function getMessage(): string 
    {
        return $this->message;
    }
}