<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Author extends Model
{
    protected $fillable = ['name'];

    public static function boot()
    {
        parent::boot();
        self::deleting(function($author) {
            // check if author has a book
            if ($author->books->count() > 0) {
                // prepare error message
                $html = "Author can't be deleted because they still have books : ";
                $html .= '<ul>';
                foreach ($author->books as $book) {
                    $html .= "<li>$book->title</li>";
                }
                $html .= '</ul>';

                Session::flash("flash_notification", [
                    "level"=>"danger",
                    "message"=>$html
                ]);

                // cancel the deletion process
                return false;
            }
        });
    }

    public function books()
    {
        return $this->hasMany('App\Book');
    }
}
