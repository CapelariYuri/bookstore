<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    // private string $name;
    // private ?int $isbn = null;
    // private ?float $value = null;
    
    private function getId(): int
    {
        return $this->id;
    }

    private function getName(): string
    {
        return $this->name;
    }

    private function setName(String $name): void
    {
       $this->name = $name;
    }

    private function getIsbn(): int
    {
        return $this->isbn;
    }

    private function setIsbn(int $isbn): void
    {
        $this->isbn = $isbn;
    }

    private function getValue(): float
    {
        return $this->value;
    }

    private function setValue(float $value): void
    {
        $this->value = $value;
    }
}
