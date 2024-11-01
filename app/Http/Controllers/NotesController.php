<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\LaravelAdapter;

class NotesController extends Controller
{
    public function __construct(){
        $this->middleware('owner')->only(['show', 'edit', 'destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        //dd(Auth::user()->notes);
        $notes = Note::where('user_id', Auth::user()->id)->get();
        $other = Auth::user()->shared;
        $notes = $notes->merge($other);
        //dd($notes);
        return view('notes.index', compact('notes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        $isEdit =  false;
        return view('notes.create-edit', compact(['isEdit']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): \Illuminate\Foundation\Application|\Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'title' => 'required|min:8|unique:notes,title',
            'description' => 'required|min:8',
            'shareWith' => 'required',

        ]);
        $note = new Note();
        $note->title = $request->input('title');
        $note->description = $request->input('description');
        $note->user_id = Auth::user()->id;

        // Save the note and check if the save was successful
        if ($note->save()) {
            $note->shared()->detach();
            $note->shared()->attach($request->input('shareWith'));
            return redirect(route('notes.show', $note->id)); // Redirect to the note's page
        } else {
            // Handle the case where the note could not be saved
            return back()->with('error', 'Failed to save the note.');
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        return view('notes.show', compact(['note']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        if ($note->user_id == Auth::id()){
            $isEdit =  true;
            return view('notes.create-edit', compact(['isEdit', 'note']));
        }else{
            abort(403);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Note $note): \Illuminate\Foundation\Application|\Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'shareWith' => 'required',
        ]);

        $note->title = $request->input('title');
        $note->description = $request->input('description');
        $note->user_id = Auth::user()->id;


        // Save the note and check if the save was successful
        if ($note->update()) {
            $note->shared()->detach();
            $note->shared()->attach($request->input('shareWith'));
            return redirect(route('notes.show', $note->id)); // Redirect to the note's page
        } else {
            // Handle the case where the note could not be saved
            return back()->with('error', 'Failed to save the note.');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note): \Illuminate\Http\RedirectResponse
    {
        $note->delete();

        return redirect()->route('home');
    }

    public function getData(): Datatables
    {
        $dt = new Datatables(new LaravelAdapter);
//        $sqlBuilder = Note::select([
//            'notes.id',
//            'notes.title',
//            'users.name',
//            'notes.created_at',
//        ])
//            ->join('users', 'users.id', '=', 'notes.user_id')
//            ->where('user_id', Auth::id());

        $sqlBuilder = " select a.id, a.title,a.created_at ,
        a.name from ((Select notes.id, notes.title, users.name,notes.created_at
        FROM notes
        join users on notes.user_id = users.id
        where notes.user_id = ".Auth::id().")
        UNION (
        Select notes.id, notes.title,
        (select name from users where users.id=notes.user_id)name,
        notes.created_at
        FROM note_user
        Join notes on notes.id = note_user.note_id
        join users on note_user.user_id = users.id
        where note_user.user_id = ".Auth::id()."
        )) a
        ";
        $dt->query($sqlBuilder);

        // edit 'id' column
        $dt->edit('title', function ($data) {
            return '<a href="'.route('notes.show',$data['id']).'">'.$data['title'].' </a> ';
        });

        // add 'action' column
        $dt->add('action', function ($data) {
            return '<a href="'.route('notes.edit', $data['id']).'">#edit </a> ';
        });
        $dt->hide('id');
        return $dt->generate();
    }
}
