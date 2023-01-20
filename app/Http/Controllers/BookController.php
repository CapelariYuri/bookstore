<?php

namespace App\Http\Controllers;

use App\Helpers\Response\JsonResponseBuilder;
use App\Http\Factory\BookFactory;
use App\Http\Requests\StoreUpdateBook;
use App\Models\Book;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class BookController extends Controller
{
    protected BookFactory $bookFactory;

    public function __construct(
        BookFactory $bookFactory
    ) {
        $this->bookFactory = $bookFactory;
    }

    /**
     * Display a listing of the resource.
     *
     * @return App\Helpers\Response\JsonResponse
     */
    public function index(): JsonResponse
    {
        $responseBuilder = new JsonResponseBuilder();

        $books = Book::all();
        
        if(is_null($books)) {
            $responseBuilder
                ->setFail()
                ->setReturnMessage('Data not found')
                ->setHttpStatusCode(404);

            return $responseBuilder();
        }

        $responseBuilder->setResponseContent($books);
        return $responseBuilder->getResponse();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param App\Http\Requests\StoreUpdateBook  $request
     * @return App\Helpers\Response\JsonResponse
     */
    public function store(StoreUpdateBook $request): JsonResponse
    {
        $responseBuilder = new JsonResponseBuilder();

        try {
            $book = $this->bookFactory->makeEntityFromJson((string) $request->getContent());
            $book->save();

            $responseBuilder
                ->setResponseContent(['id' => $book->id])
                ->setReturnMessage('successful registration');

        } catch (Exception $e) {
            $responseBuilder
                ->setFail()
                ->setHttpStatusCode(500)
                ->setReturnMessage($e->getMessage());

            return $responseBuilder->getResponse();
        }

        return $responseBuilder->getResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return App\Helpers\Response\JsonResponse
     */
    public function show($id): JsonResponse
    {
        $responseBuilder = new JsonResponseBuilder();

        $book = book::find($id);

        if (is_null($book)) {
            $responseBuilder
                ->setReturnMessage('Data not found')
                ->setHttpStatusCode(404);
        }

        $responseBuilder->setResponseContent($book);

        return $responseBuilder->getResponse();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param App\Http\Requests\StoreUpdateBook  $request
     * @param int  $id
     * @return App\Helpers\Response\JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $responseBuilder = new JsonResponseBuilder();
        $existingBook = book::find($id);

        if ($existingBook === null) {
            $responseBuilder
                ->setReturnMessage('Data not found')
                ->setHttpStatusCode(404);
            return $responseBuilder->getResponse();
        }

        try {
            $bookSent = $this->bookFactory->makeEntityFromJson((string) $request->getContent());
        } catch (Exception $e) {
            $responseBuilder
                ->setFail()
                ->setReturnMessage($e->getMessage())
                ->setHttpStatusCode(500);
            return $responseBuilder->getResponse();
        }

        $this->updateExistingBook($existingBook, $bookSent);

        try {
            $existingBook->save();
        } catch (Exception $e) {
            $responseBuilder
                ->setFail()
                ->setReturnMessage($e->getMessage())
                ->setHttpStatusCode(500);
            return $responseBuilder->getResponse();
        }

        $responseBuilder->setReturnMessage('Updated registration');

        return $responseBuilder->getResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return App\Helpers\Response\JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $responseBuilder = new JsonResponseBuilder();

        $existingBook = book::find($id);
        if (is_null($existingBook)) {
            $responseBuilder
                ->setReturnMessage('Data not found')
                ->setHttpStatusCode(404);
            return $responseBuilder->getResponse();
        }

        $existingBook->delete();

        $responseBuilder->setReturnMessage('Record deleted');

        return $responseBuilder->getResponse();
    }

    protected function updateExistingBook($existingBook, $bookSent): void
    {
        $existingBook->name = $bookSent->name;
        $existingBook->isbn = $bookSent->isbn;
        $existingBook->value = $bookSent->value;
    }
}
