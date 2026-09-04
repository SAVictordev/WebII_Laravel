<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name'])]

class Author extends Model
{
    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}