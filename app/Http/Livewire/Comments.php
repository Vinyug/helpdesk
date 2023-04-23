<?php

namespace App\Http\Livewire;

use App\Models\Comment;
use App\Models\Ticket;
use App\Models\Upload;
use App\Models\User;
use App\Notifications\NewComment;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


class Comments extends Component
{
    use WithFileUploads;

    public $ticket;
    public $comments;
    public $input = [];
    public $filenames = [];
    public $ticket_number_separate = '/';
    public $thumbnail_width = 100;
    public $thumbnail_height = 100;
    public $resolved = 'Résolu';
    
    public $storeSubmitted = false;
    public $updateSubmitted = false;
    public $editMode = false;


    public function mount(Ticket $ticket)
    {
        $this->ticket = $ticket;
        $this->comments = Comment::where('ticket_id', '=', $ticket->id)->latest()->get();
    }

    public function store(Ticket $ticket)
    {
        if($this->ticket->state !== $this->resolved) {
            
            $this->storeSubmitted = true;      

            // ---------------------------------------------------------------
            // ---------------------- DATA VALIDATION ------------------------
            // ---------------------------------------------------------------
            $validatedData = $this->validate([
                'input.content' => 'required|max:2000',
                'input.filenames.*' => 'sometimes|file|mimes:jpg,jpeg,png,bmp|max:2000|dimensions:min_width='.$this->thumbnail_width.',min_height='.$this->thumbnail_height,
                'input.time_spent' => 'sometimes|nullable|numeric|regex:/^\d{1,4}(\.\d{1,2})?$/', //number 0000.00
            ], [
                'input.time_spent.regex' => 'Le champ temps passé doit être un nombre respectant dans sa valeur maximum cette syntaxe 0000.00'
            ]);
            

            // ---------------------------------------------------------------
            // --------------------------- VARIABLE --------------------------
            // ---------------------------------------------------------------

            // get user_id
            $user_id = auth()->user()->id;
            // get ticket_id
            $ticket_id = $this->ticket->id;
            // get ticket_number
            $ticket_number = $this->ticket->ticket_number;
             

            // ---------------------------------------------------------------
            // --------------------------- INSERT ----------------------------
            // ---------------------------------------------------------------
            
            // --------------------------- COMMENT ---------------------------
            $comment = Comment::create(array_merge($this->input, compact('user_id', 'ticket_id')));
            
            // ---------------------- TICKET (UPDATE) ------------------------
            $ticket = Ticket::find($ticket_id);
            // update 'updated_at'
            $ticket->touch();
            
            
            // --------------------------- UPLOAD ----------------------------
            
            // get comment_id
            $comment_id = $comment->id;
            
            // verify files if file exist and isValid, to insert in DB
            if (array_key_exists('filenames', $this->input) && $this->input['filenames']) {
                $i = 0;
                foreach ($this->input['filenames'] as $file) {
                    if ($file->isValid()) {
                        // get file extension
                        $ext = $file->extension();
                        // rename each file
                        $name = str_replace(['#', $this->ticket_number_separate], ['', '-'], $ticket_number) . '_' . $i . '.' . $ext;
                        $i++;

                        // upload each file in folder named by ticket number and comment id
                        $path = $file->storeAs('files/ticket-' . str_replace(['#', $this->ticket_number_separate], ['', '-'], $ticket_number) . '/comment-' . $comment_id, $name);

                        // resize thumbnail
                        $thumbnailFile = Image::make($file)->fit($this->thumbnail_width, $this->thumbnail_height, function ($constraint) {
                            $constraint->upsize();
                        })->encode($ext, 50); //reduce sizing by 50%
                        // thumbnail path
                        $thumbnailPath = 'files/ticket-' . str_replace(['#', $this->ticket_number_separate], ['', '-'], $ticket_number) . '/comment-' . $comment_id . '/thumbnail/thumb_' . $name;
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
            
            // Remove all temporary files
            File::cleanDirectory(storage_path('app/public/livewire-tmp/'));


            // ---------------------------------------------------------------
            // ------------------------ NOTIFICATION -------------------------
            // ---------------------------------------------------------------

            // Notify author of ticket, admin, and admin company of company
            $listOfUsersNotifiable = $this->listOfUsersNotifiable($comment);
            
            if(env('MAIL_USERNAME')) {
                Notification::send($listOfUsersNotifiable, new NewComment($comment));
            }


            // ---------------------------------------------------------------
            // ---------------------------- RESET ----------------------------
            // ---------------------------------------------------------------
            
            $this->reset('input');
            $this->comments = Comment::where('ticket_id', '=', $ticket_id)->latest()->get();
            
            $this->storeSubmitted = false;
            
            session()->flash('success', 'Le commentaire a été enregistré avec succès.');

        } else {

            return redirect()->back()->with('status','Le ticket est cloturé, vous ne pouvez plus créer de commentaire.');

        }
    }

    public function edit($id)
    {
        $this->editMode = !$this->editMode;

        $comment = Comment::find($id);
        
        $this->input = [
            'content' => $comment->content,
            'time_spent' => $comment->time_spent,
        ];
        
    }

    public function cancel()
    {
        $this->editMode = false;
        $this->reset('input');
    }

    public function update(Comment $comment)
    {   

        if(auth()->user()->id === $comment->user_id && $this->ticket->state !== $this->resolved) {
           
            $this->updateSubmitted = true;      

            // ---------------------------------------------------------------
            // ---------------------- DATA VALIDATION ------------------------
            // ---------------------------------------------------------------
            $validatedData = $this->validate([
                'input.content' => 'required|max:2000',
                'input.filenames.*' => 'sometimes|file|mimes:jpg,jpeg,png,bmp|max:2000|dimensions:min_width='.$this->thumbnail_width.',min_height='.$this->thumbnail_height,
                'input.time_spent' => 'sometimes|nullable|numeric|regex:/^\d{1,4}(\.\d{1,2})?$/', //number 0000.00
            ], [
                'input.time_spent.regex' => 'Le champ temps d\'intervention doit être un nombre respectant dans sa valeur maximum cette syntaxe 0000.00'
            ]);
            

            // ---------------------------------------------------------------
            // --------------------------- VARIABLE --------------------------
            // ---------------------------------------------------------------

            // get ticket_id
            $ticket_id = $this->ticket->id;
            // get ticket_number
            $ticket_number = $this->ticket->ticket_number;
            // get comment_id
            $comment_id = $comment->id;
            
            // ---------------------------------------------------------------
            // --------------------------- UPDATE ----------------------------
            // ---------------------------------------------------------------
            
            // --------------------------- COMMENT ----------------------------

            $comment = Comment::find($comment->id);
            $comment->update($this->input);

            
            // --------------------------- UPLOAD ----------------------------
            
            // verify files if file exist and isValid, to insert in DB
            if (array_key_exists('filenames', $this->input) && $this->input['filenames']) {
                // delete every uploads of comment
                $uploads = Upload::where('comment_id', $comment_id)->get();
                foreach ($uploads as $upload) {
                    Storage::delete([$upload->path, $upload->thumbnail_path]);
                    $upload->delete();
                }
                
                // Insert new uploads
                $i = 0;
                foreach ($this->input['filenames'] as $file) {
                    if ($file->isValid()) {
                        // get file extension
                        $ext = $file->extension();
                        // rename each file
                        $name = str_replace(['#', $this->ticket_number_separate], ['', '-'], $ticket_number) . '_' . $i . '.' . $ext;
                        $i++;

                        // upload each file in folder named by ticket number and comment id
                        $path = $file->storeAs('files/ticket-' . str_replace(['#', $this->ticket_number_separate], ['', '-'], $ticket_number) . '/comment-' . $comment_id, $name);

                        // resize thumbnail
                        $thumbnailFile = Image::make($file)->fit($this->thumbnail_width, $this->thumbnail_height, function ($constraint) {
                            $constraint->upsize();
                        })->encode($ext, 50); //reduce sizing by 50%
                        // thumbnail path
                        $thumbnailPath = 'files/ticket-' . str_replace(['#', $this->ticket_number_separate], ['', '-'], $ticket_number) . '/comment-' . $comment_id . '/thumbnail/thumb_' . $name;
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
            
            // Remove all temporary files
            File::cleanDirectory(storage_path('app/public/livewire-tmp/'));


            // ---------------------------------------------------------------
            // ---------------------------- RESET ----------------------------
            // ---------------------------------------------------------------
            
            $this->reset('input');
            $this->comments = Comment::where('ticket_id', '=', $ticket_id)->latest()->get();
            
            $this->updateSubmitted = false;
            $this->editMode = false;
            
            session()->flash('success', 'Le commentaire a été modifié avec succès.');

        } else {

            return redirect()->back()->with('status','Le ticket est cloturé, vous ne pouvez plus modifier un commentaire.');

        }
    }

    public function delete($id)
    {
        $this->editMode = false;
        $this->reset('input');
        $ticket_id = $this->ticket->id;

        if($id){
            Comment::where('id',$id)->delete();
            $this->comments = Comment::where('ticket_id', '=', $ticket_id)->latest()->get();

            session()->flash('success', 'Le commentaire a bien été supprimé.');
        }
    }

    public function render()
    {
        return view('livewire.comments')->with([
            'storeSubmitted', $this->storeSubmitted,
            'updateSubmitted', $this->updateSubmitted,
            'editMode', $this->editMode,
        ]);
    }

    public function listOfUsersNotifiable(Comment $comment)
    {
        // -------------- ADMIN ---------------
        // get users have all-access
        $admin = User::permission('all-access')->get();
        // ------------- COMPANY --------------
        // get author of ticket
        $authorTicket = $comment->ticket->user;
        // get admin company
        $adminCompany = User::permission('ticket-private')
        ->where('company_id','=', $comment->ticket->company_id)
        ->get(); 
        // merge to send
        $authorTicketAdminAndAdminCompany = collect([$authorTicket])->merge($admin)->merge($adminCompany)->unique('id');
        
        return $authorTicketAdminAndAdminCompany;
    }
    
}
