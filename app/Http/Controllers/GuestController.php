<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use App\Book;
use Laratrust\LaratrustFacade as Laratrust;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\BorrowLog;
use Illuminate\Support\Facades\Auth;
use Session;

class GuestController extends Controller
{
    public function index(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) {
            $books = Book::with('author');
            return Datatables::of($books)
                ->addColumn('action', function($book){
                    if (Laratrust::hasRole('admin')) {
                        return '';
                    }
                    return '<a class="btn btn-xs btn-primary" href="'.route('guest.books.borrow', $book->id).'">Borrow</a>';
                })->make(true);
        }

        $html = $htmlBuilder
        ->addColumn([
            'data' => 'title',
            'name' => 'title',
            'title' => 'Title'
        ])
        ->addColumn([
            'data' => 'author.name',
            'name' => 'author.name',
            'title' => 'Author'
        ])
        ->addColumn([
            'data' => 'action',
            'name' => 'action',
            'title' => '',
            'orderable' => false,
            'searchable' => false
        ]);

        return view('guest.index')->with(compact('html'));
    }

    public function borrow($id)
    {
        try {
            $book = Book::findOrFail($id);
            BorrowLog::create([
                'user_id' => Auth::user()->id,
                'book_id' => $id
            ]);

            Session::flash("flash_notification", [
                "level"=>"success",
                "message"=>"Succesfully borrow the $book->title"
            ]);
        } catch (ModelNotFoundException $e) {
            Session::flash("flash_notification", [
                "level"=>"danger",
                "message"=>"Book not found."
            ]);
        }

        return redirect('/');
    }
}
