<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksApiTest extends TestCase
{
    
    use RefreshDatabase;

    /** @test */
    function can_get_all_books()
    {
        $books = Book::factory(4)->create();

        $response = $this->getJson(route('books.index'));

        $response->assertJsonFragment([
            'title' => $books[0]->title,
            'synopsis' => $books[0]->synopsis
        ])
        ->assertJsonFragment([
            'title' => $books[1]->title,
            'synopsis' => $books[1]->synopsis
        ]);
    }

    /** @test */
    function can_get_one_book()
    {
        $book = Book::factory()->create();

        $response = $this->getJson(route('books.show', $book));

        $response->assertJsonFragment([
            'title' => $book->title,
            'synopsis' => $book->synopsis
        ]);
        
    }

    /** @test */
    function can_get_create_book()
    {
        $this->postJson(route('books.store'), [])

         ->assertJsonValidationErrors(['title', 'synopsis']);
           

        $this->postJson(route('books.store'), [
            'title' => 'My new book',
            'synopsis' => 'Synopsis of My new book'
        ])->assertJsonFragment([
            'title' => 'My new book',
            'synopsis' => 'Synopsis of My new book'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'My new book',
            'synopsis' => 'Synopsis of My new book'
        ]);
    }

    /** @test */
    function can_get_update_books()
    {
        $book = Book::factory()->create();

        $this->patchJson(route('books.update', $book), [])

            ->assertJsonValidationErrors(['title', 'synopsis']);

       $this->patchJson(route('books.update', $book), [
            'title' => 'Edited book',
            'synopsis' => 'Synopsis Edited Book'
        ])->assertJsonFragment([
            'title' => 'Edited book',
            'synopsis' => 'Synopsis Edited Book'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'Edited book',
            'synopsis' => 'Synopsis Edited Book'
        ]);

    }

    /** @test */
    function can_get_delete_books()
    {
        $book = Book::factory()->create();

        $this->deleteJson(route('books.destroy', $book))

            ->assertNoContent();

        $this->assertDatabaseCount('books', 0);
    }

}
