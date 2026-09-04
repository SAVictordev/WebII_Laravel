<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

#[fillable([
    'title',
    'isbn',
    'published_year',
    'author_id',
    ])];

class Book extends Model
{
    // metodo de relacionamento 1 para 1 com o BookDetail
    public function detail(): HasOne{
        return $this->hasOne(BookDetail::class);
    }

    // metodo de relacionamento 1 para N com author
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }
    // metodo para tratamento dos dados recebidos por isbn
    protected function isbn(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) =>
            str_replace(['-', ' '], '', $value),
        );
    }
}


/* Na Linha de comando use para criar um objetos:

php artisan tinker
use App\Models\Book

$book = new Book();
$book->title = 'O Hobbit';
$book->isbn = '9788595084742';
$book->published_year = 1937;
$book->save();

*/