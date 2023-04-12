<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
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
            'visibility' => 'boolean',
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
        // get hourly_rate
        $hourly_rate = Listing::whereNotNull('hourly_rate')
            ->where('hourly_rate','!=', '')
            ->pluck('hourly_rate')
            ->last();
        
        
        // ---------------------------------------------------------------
        // --------------------------- INSERT ----------------------------
        // ---------------------------------------------------------------
        
        // --------------------------- TICKET ----------------------------
        $ticket = Ticket::create(array_merge([
            'subject' => $request['subject'],
            'service' => $request['service'],
            'visibility' => $request['visibility'] ? 0 : 1,
        ], 
        compact('user_id', 'company_id', 'ticket_number', 'uuid', 'hourly_rate')));
        
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
        // get ticket id
        $ticket_id = $ticket->id;
        // get ticket hourly_rate
        $hourlyRate = $ticket->hourly_rate;
        // get all comments of ticket
        $comments = Comment::where('ticket_id', '=', $ticket->id)->latest()->get();
        // get states of listing
        $states = Listing::whereNotIn('state', ['Non lu', 'Lu', ''])
            ->whereNotNull('state')
            ->distinct()
            ->pluck('state');
        
        // if user have all-access, ticket-private or (user belongs to a company and ticket is public) or (user is author and ticket is private)
        if (auth()->user()->can('all-access') || auth()->user()->can('ticket-private') || (auth()->user()->company_id === $ticket->company_id && $ticket->visibility) || (auth()->user()->id === $ticket->user_id && !$ticket->visibility)) {
            
            $this->verifyTicketCanEditable($ticket, $comments);
            // Total time and price on ticket
            $totalTime = $this->calculateTicketTotalTime($ticket_id);
            $totalPrice = $this->calculateTicketTotalPrice($hourlyRate, $totalTime);
            
            return view('tickets.show',compact('ticket', 'comments', 'states', 'totalTime', 'totalPrice'));
        }

        // return error http
        return abort('403', 'Vous n\'avez pas la permission d\'accéder à cette page');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Ticket $ticket)
    {
        
        if ((Auth()->user()->id === $ticket->user_id) && ($ticket->editable)) {
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
        if(auth()->user()->id === $comment->user_id) {
            $request->validate([
                'company_id' => 'required|exists:companies,id',
                'subject' => 'required|max:80',
                'service' => 'required|exists:listings,service',
                'content' => 'required',
                'visibility' => 'boolean',
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
                'visibility' => $request['visibility'] ? 0 : 1,
            ]);
            $ticket->update();
            
            // DB comments
            $comment->fill([
                'content' => $request['content'],
            ]);
            $comment->update();

            return redirect()->route('tickets.index')->with('success','Le ticket a été mis à jour avec succès.');
        }

        return redirect()->route('tickets.index')->with('status','Vous n\'avez pas l\'autorisation de modifier ce ticket.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        if ((Auth()->user()->id === $ticket->user_id) && ($ticket->editable)) {
            $ticket->delete();
         
            return redirect()->route('tickets.index')->with('success','Le ticket a été supprimé avec succès');
        }
        
        return redirect()->route('tickets.index')->with('status','Vous n\'avez pas l\'autorisation de supprimer ce ticket.');
    }


    /**
     * Custom method
     *
     */

    // Generate Ticket Number
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

    // Verify if ticket, can be editable
    public function verifyTicketCanEditable(Ticket $ticket, $comments)
    {
        // get last comment of ticket (first() cause DESC)
        $lastComment = $comments->first();
        // Modify state of ticket
        $ticketSeen = 'Lu';
        $ticketNotSeen = 'Non Lu';
        
        // if user with all-access and ticket user_id IS NOT this user, open ticket. Author can not modify his ticket
        if ((auth()->user()->can('all-access')) && (auth()->user()->id !== $ticket->user_id)) {
            // update ticket
            $this->ticketEditableLocked($ticket, $ticketSeen, $ticketNotSeen);
            
            // update all comments of the ticket
            if ($lastComment->user_id === auth()->user()->id) {
                // if the last comment is by the user, keep it editable
                foreach ($comments as $comment) {
                    if ($comment->id !== $lastComment->id) {
                        $this->commentEditableLocked($comment);
                    }
                }
                
            } else {
                // update all comments of the ticket
                foreach ($comments as $comment) {
                    $this->commentEditableLocked($comment);
                }
            }
        }
        
        // if user with all-access and ticket user_id IS this user, open ticket. Author can not modify his ticket
        if ((auth()->user()->can('all-access')) && (auth()->user()->id === $ticket->user_id)) {
            // get number of comments
            $numberComments = count($comments);
            
            // if number of comment > 1, ticket is not editable
            if ($numberComments > 1) {
                // update ticket
                $this->ticketEditableLocked($ticket, $ticketSeen, $ticketNotSeen);
            }
            
            // if the last comment is by the user, keep it editable
            if ($lastComment->user_id === auth()->user()->id) {
                foreach ($comments as $comment) {
                    if ($comment->id !== $lastComment->id) {
                        $this->commentEditableLocked($comment);
                    }
                }
                
            } else {
                // update all comments of the ticket
                foreach ($comments as $comment) {
                    $this->commentEditableLocked($comment);
                }
            }
        }
    }

    public function ticketEditableLocked(Ticket $ticket, $ticketSeen, $ticketNotSeen)
    {
        if($ticketNotSeen === $ticket->state) {
            $ticket->editable = 0; 
            $ticket->state = $ticketSeen; 
            $ticket->save();
        }
    }

    public function commentEditableLocked($comment)
    {
        // updated_at lock
        $comment->timestamps = false;
        $comment->editable = 0;
        $comment->save();
        // updated_at unlock
        $comment->timestamps = true;
    }

    public function calculateTicketTotalTime($ticket_id)
    {
        $commentTimes = Comment::where('ticket_id', $ticket_id)
            ->whereNotNull('time_spent')
            ->where('time_spent', '!=', '')
            ->pluck('time_spent');
        $totalTime = 0;
               
        foreach($commentTimes as $commentTime) {
            $totalTime += $commentTime;
        }

        return $totalTime;
    }

    public function calculateTicketTotalPrice($hourlyRate, $totalTime)
    {
        $totalPrice = $totalTime * $hourlyRate;

        return number_format($totalPrice, 2, ',', '.');
    }
}
