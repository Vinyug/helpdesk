<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Company;
use App\Models\Listing;
use App\Models\Ticket;
use App\Models\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;


class TicketController extends Controller
{
    /**
     * Variable.
     *
     */
    protected $ticket_number_separate = '/';
    protected $thumbnail_width = 100;
    protected $thumbnail_height = 100;


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:ticket-list|ticket-create|ticket-edit|ticket-delete', ['only' => ['index','show']]);
         $this->middleware('permission:ticket-create', ['only' => ['create','store']]);
         $this->middleware('permission:ticket-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:ticket-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // query in powergrid table : TicketTable
        return view('tickets.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::get();
        $services = Listing::whereNotNull('service')->where('service','!=', '')->pluck('service', 'service');

        return view('tickets.create', compact('companies', 'services'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // ---------------------------------------------------------------
        // ---------------------- DATA VALIDATION ------------------------
        // ---------------------------------------------------------------
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'subject' => 'required|max:80',
            'service' => 'required|exists:listings,service',
            'content' => 'required',
            'filename.*' => 'sometimes|file|mimes:jpg,jpeg,png,bmp|max:2000|dimensions:min_width='.$this->thumbnail_width.',min_height='.$this->thumbnail_height,
        ]);
        
        // dd(Auth::user()->id);
        // dd($request);
        

        // ---------------------------------------------------------------
        // --------------------------- VARIABLE --------------------------
        // ---------------------------------------------------------------
        // user_id
        $user_id = Auth::user()->id;
        // company_id
        if (Auth::user()->can('all-access')) {
            $company_id = $request['company_id'];
        } else {
            $company_id = Auth::user()->company_id;
        }
        // generate ticket number
        $ticket_number = $this->generateTicketNumber();
        // genererate uuid
        $uuid = Str::uuid()->toString();
        

        // ---------------------------------------------------------------
        // --------------------------- INSERT ----------------------------
        // ---------------------------------------------------------------
        
        // --------------------------- TICKET ----------------------------
        $ticket = Ticket::create(array_merge([
            'subject' => $request['subject'],
            'service' => $request['service'],
        ], 
        compact('user_id', 'company_id', 'ticket_number', 'uuid')));
        
        // --------------------------- COMMENT ----------------------------
        // get ticket_id
        $ticket_id = $ticket->id;
        
        // insert
        $comment = Comment::create(array_merge([
            'content' => $request['content'],
        ], 
        compact('user_id', 'ticket_id')));
        
        // --------------------------- UPLOAD ----------------------------
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

        return redirect()->route('tickets.show', $uuid)->with('success','Le ticket a été enregistré avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        // get all comments of ticket
        $comments = Comment::where('ticket_id', '=', $ticket->id)->latest()->get();

        return view('tickets.show',compact('ticket', 'comments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Ticket $ticket)
    {
        // add super admin and admin company
        if (Auth()->user()->id == $ticket->user_id) {
            $services = Listing::whereNotNull('service')->where('service','!=', '')->pluck('service', 'service');
            $comment = Comment::where('ticket_id', '=', $ticket->id)->first();
            $companies = Company::get();
            
            return view('tickets.edit',compact('ticket', 'services', 'comment', 'companies'));
        }
        
        return redirect()->route('tickets.index')->with('status','Vous n\'avez pas l\'autorisation de modifier ce ticket.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ticket $ticket, Comment $comment)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'subject' => 'required|max:80',
            'service' => 'required|exists:listings,service',
            'content' => 'required',
        ]);
        
        // company_id
        if (Auth::user()->can('all-access')) {
            $ticket->fill([
                'company_id' => $request['company_id'],
            ]);
        }

        //------ UPDATE --------
        // DB tickets
        $ticket->fill([
            'subject' => $request['subject'],
            'service' => $request['service'],
        ]);
        $ticket->update();
        
        // DB comments
        $comment->fill([
            'content' => $request['content'],
        ]);
        $comment->update();

        return redirect()->route('tickets.index')->with('success','Le ticket a été mis à jour avec succès.');
 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('tickets.index')->with('success','Le ticket a été supprimé avec succès');
    }


    /**
     * Custom method
     *
     */
    public function generateTicketNumber()
    {
        // user nesbot/carbon to know date of day
        $date = Carbon::now();
        // format into ddmmyy
        $dateFormat = $date->format('dmy');
        // count the number of tickets of the day
        $dayTicket = Ticket::whereDate('created_at', $date)->count();
        // and increment it
        $dayTicket++;
        // format ticket number on format - #ddmmyy/i
        $ticket_number = "#{$dateFormat}/{$dayTicket}";
        
        return $ticket_number;
    }

}
