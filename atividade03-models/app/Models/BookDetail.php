<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['book_id', 'pages', 'summary'])]

class BookDetail extends Model
{
    public function book(): BelongsTo {
        return $this->belongsTo(Book::class);
    }
};

