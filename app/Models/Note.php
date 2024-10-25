<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

//    public mixed $id;
//    public mixed $description;
//    public mixed $title;
    protected $fillable = ['title', 'description'];



}
