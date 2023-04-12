<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ticket;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class CommentController extends Controller
{
    /**
     * Variable.
     *
     */
    protected $ticket_number_separate = '/';
    protected $thumbnail_width = 100;
    protected $thumbnail_height = 100;
    protected $resolved = 'Résolu';


     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // function __construct()
    // {
    //      $this->middleware('permission:comment-list|comment-create|comment-edit|comment-delete', ['only' => ['index','show']]);
    //      $this->middleware('permission:comment-create', ['only' => ['create','store']]);
    //      $this->middleware('permission:comment-edit', ['only' => ['edit','update']]);
    //      $this->middleware('permission:comment-delete', ['only' => ['destroy']]);
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Ticket $ticket)
    {
        if($ticket->state !== $this->resolved) {
            // ---------------------------------------------------------------
            // ---------------------- DATA VALIDATION ------------------------
            // ---------------------------------------------------------------
            $request->validate([
                'content' => 'required|max:2000',
                'filename.*' => 'sometimes|file|mimes:jpg,jpeg,png,bmp|max:2000|dimensions:min_width='.$this->thumbnail_width.',min_height='.$this->thumbnail_height,
                'time_spent' => 'sometimes|nullable|numeric|regex:/^\d{1,4}(\.\d{1,2})?$/', //number 0000.00
            ], [
                'time_spent.regex' => 'Le champ temps passé doit être un nombre respectant dans sa valeur maximum cette syntaxe 0000.00'
            ]);

            // dd($request);


            // ---------------------------------------------------------------
            // --------------------------- VARIABLE --------------------------
            // ---------------------------------------------------------------
            // get user_id
            $user_id = Auth::user()->id;
            // get ticket_id
            $ticket_id = $ticket->id;
            
            
            // ---------------------------------------------------------------
            // --------------------------- INSERT ----------------------------
            // ---------------------------------------------------------------
            
            // --------------------------- COMMENT ----------------------------
            $comment = Comment::create(array_merge($request->post(), compact('user_id', 'ticket_id')));
            
            // --------------------------- UPLOAD ----------------------------
            // get ticket_number
            $ticket_number = $ticket->ticket_number;
            // get comment_id
            $comment_id = $comment->id;

            // verify files if file exist and isValid, to insert in DB
            if ($request->hasFile('filename')) {
                $i = 0;
                foreach ($request->file('filename') as $file) {
                    if ($file->isValid()) {
                        // get file extension
                        $ext = $file->extension();
                        // rename each file
                        $name = str_replace(['#', $this->ticket_number_separate], ['', '-'], $ticket_number).'_'.$i.'.'.$ext;
                        $i++;

                        // upload each file in folder named by ticket number and comment id
                        $path = $file->storeAs('files/ticket-'.str_replace(['#', $this->ticket_number_separate], ['', '-'], $ticket_number).'/comment-'.$comment_id, $name);

                        // resize thumbnail
                        $thumbnailFile = Image::make($file)->fit($this->thumbnail_width, $this->thumbnail_height, function($constraint){
                            $constraint->upsize();
                        })->encode($ext, 50); //reduce sizing by 50%
                        // thumbnail path
                        $thumbnailPath = 'files/ticket-'.str_replace(['#', $this->ticket_number_separate], ['', '-'], $ticket_number).'/comment-'.$comment_id.'/thumbnail/thumb_'.$name;
                        // stock file. first parameter : where ; second parameter : what
                        Storage::put($thumbnailPath, $thumbnailFile);

                        // insert
                        $upload = Upload::create(array_merge([
                            'filename' => $name,
                            'url' => Storage::url($path),
                            'path' => $path,
                            'thumbnail_url' => Storage::url($thumbnailPath),
                            'thumbnail_path' => $thumbnailPath,
                        ], 
                        compact('comment_id')));
                    }
                }
            }


            // ---------------------------------------------------------------
            // ---------------------------- VIEW -----------------------------
            // ---------------------------------------------------------------

            return redirect()->back()->with('success','Le commentaire a été enregistré avec succès.');
        }
    
        return redirect()->back()->with('status','Le ticket est cloturé, vous ne pouvez plus créer un commentaire.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Upload $upload, Comment $comment, Ticket $ticket)
    {
            if(auth()->user()->id === $comment->user_id && $ticket->state !== $this->resolved) {
            // ---------------------------------------------------------------
            // ---------------------- DATA VALIDATION ------------------------
            // ---------------------------------------------------------------
            $request->validate([
                'content' => 'required|max:2000',
                'filename.*' => 'sometimes|file|mimes:jpg,jpeg,png,bmp|max:2000|dimensions:min_width='.$this->thumbnail_width.',min_height='.$this->thumbnail_height,
                'time_spent' => 'sometimes|nullable|numeric|regex:/^\d{1,4}(\.\d{1,2})?$/', //number 0000.00
            ], [
                'time_spent.regex' => 'Le champ temps passé doit être un nombre respectant dans sa valeur maximum cette syntaxe 0000.00'
            ]);


            // ---------------------------------------------------------------
            // --------------------------- VARIABLE --------------------------
            // ---------------------------------------------------------------
            // get ticket_number
            $ticket_number = $ticket->ticket_number;
            

            // ---------------------------------------------------------------
            // --------------------------- UPDATE ----------------------------
            // ---------------------------------------------------------------
            
            // --------------------------- COMMENT ----------------------------
            $comment->fill([
                'content' => $request['content'],
                'time_spent' => $request['time_spent'],
            ]);
            $comment->update();


            // --------------------------- UPLOAD ----------------------------
            // get ticket_number
            $ticket_number = $ticket->ticket_number;
            // get comment_id
            $comment_id = $comment->id;

            // verify files if file exist and isValid, to delete upload and insert in DB
            if ($request->hasFile('filename')) {

                // delete every uploads of comment
                $uploads = Upload::where('comment_id', $comment_id)->get();
                foreach ($uploads as $upload) {
                    Storage::delete([$upload->path, $upload->thumbnail_path]);
                    $upload->delete();
                }
                
                
                // Insert new uploads comment
                $i = 0;
                foreach ($request->file('filename') as $file) {
                    if ($file->isValid()) {
                        // get file extension
                        $ext = $file->extension();
                        // rename each file
                        $name = str_replace(['#', $this->ticket_number_separate], ['', '-'], $ticket_number).'_'.$i.'.'.$ext;
                        $i++;

                        // upload each file in folder named by ticket number and comment id
                        $path = $file->storeAs('files/ticket-'.str_replace(['#', $this->ticket_number_separate], ['', '-'], $ticket_number).'/comment-'.$comment_id, $name);

                        // resize thumbnail
                        $thumbnailFile = Image::make($file)->fit($this->thumbnail_width, $this->thumbnail_height, function($constraint){
                            $constraint->upsize();
                        })->encode($ext, 50); //reduce sizing by 50%
                        // thumbnail path
                        $thumbnailPath = 'files/ticket-'.str_replace(['#', $this->ticket_number_separate], ['', '-'], $ticket_number).'/comment-'.$comment_id.'/thumbnail/thumb_'.$name;
                        // stock file. first parameter : where ; second parameter : what
                        Storage::put($thumbnailPath, $thumbnailFile);

                        // insert
                        $upload = Upload::create(array_merge([
                            'filename' => $name,
                            'url' => Storage::url($path),
                            'path' => $path,
                            'thumbnail_url' => Storage::url($thumbnailPath),
                            'thumbnail_path' => $thumbnailPath,
                        ], 
                        compact('comment_id')));
                    }
                }
            }


            // ---------------------------------------------------------------
            // ---------------------------- VIEW -----------------------------
            // ---------------------------------------------------------------

            return redirect()->back()->with('success','Le commentaire a été modifié avec succès.');
        }

        return redirect()->back()->with('status','Vous n\'avez pas l\'autorisation de modifier ce commentaire.');
    }
        
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment, Ticket $ticket)
    {
        if(auth()->user()->id === $comment->user_id && $ticket->state !== $this->resolved) {
            $comment->delete();
            return redirect()->back()->with('success','Le commentaire a été supprimé avec succès.');
        }

        return redirect()->back()->with('status','Vous n\'avez pas l\'autorisation de supprimer ce commentaire.');
    }
}
