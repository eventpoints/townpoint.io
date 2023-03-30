<?php

namespace App\Model;

use Symfony\Component\Uid\Uuid;

class SubscriptionPlan
{
    private string $id;
    private string $name;
    private int $price;

    public function __construct($id, $name, $price)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }
    public function getId() : string
    {
        return $this->id;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getPrice() : int
    {
        return $this->price;
    }
}