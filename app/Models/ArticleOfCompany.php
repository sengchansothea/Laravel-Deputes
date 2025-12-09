<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleOfCompany extends Model
{
    use HasFactory;
    public $table="tbl_article_of_company";
    public $timestamps=false;
    protected $guarded = [];

}
