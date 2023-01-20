<?php

namespace App\Http\Factory;

use App\Models\Book;
use Exception;

class BookFactory
{
    public function makeEntityFromJson(string $requestJson): Book
    {
        $jsonData = json_decode($requestJson);

        $this->validProperties($jsonData);

        $book = new Book();

        $book->name = $jsonData->name;
        $book->isbn = $jsonData->isbn;
        $book->value = $jsonData->value;

        return $book;
    }

    private function validProperties(object $jsonData): void
    {
        if (!property_exists($jsonData, 'name')) {
            throw new Exception('To register a book it is necessary to inform a name.');
        }
    }
}
