<?php
    
namespace App\Http\Controllers;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Listing;
use App\Models\User;
use App\Notifications\AssignCompanyUser;
use App\Notifications\NewUser;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','show']]);
         $this->middleware('permission:user-create', ['only' => ['create','store']]);
         $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::with('company');

        return view('users.index', compact('users'));
        
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::get();
        $companies = Company::get();
        $jobs = Listing::whereNotNull('job')->where('job','!=', '')->pluck('job', 'job');

        return view('users.create',compact('roles', 'companies', 'jobs'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // if user have not all-access, only roles 2 (User) and/or 3 (admin-company) can be transmit.
        $validRoles = collect([]);
        if (auth()->user()->can('all-access')) {
            $validRoles = Role::pluck('id');
        } else {
            $validRoles = Role::whereIn('id', [2, 3])->pluck('id');
        }

        // validation
        $this->validate($request, [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'company_id' => 'required|exists:companies,id',
            'job' => 'exists:listings,job|nullable',
            'roles' => ['required', 'array', Rule::in($validRoles),
            ],
        ]);
    
        // verify if checked return 1
        $active = !isset($request->active) ? 0 : 1;
        
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $input['active'] = $active;
        
        // if user have not all-access companies.id  = user.company_id
        if (!auth()->user()->can('all-access')) {
            $input['company_id'] = auth()->user()->company_id;
        }
        
        // insert user
        $user = User::create($input);
        // assign roles at user
        $user->assignRole($request->input('roles'));
    
        // ---------------- Notification ---------------------
        // Notify new user, super admin, and admin company of company
        // get users admin have all-access
        $admin = User::permission('all-access')->get();
        // get users admin company of company
        $adminCompany = User::permission('ticket-private')
            ->where('company_id','=', $user->company_id)
            ->get(); 
        // merge to send
        $userAdminAndAdminCompany = collect([$user])->merge($admin)->merge($adminCompany);;

        if(env('MAIL_USERNAME')) {
            Notification::send($userAdminAndAdminCompany, new NewUser($user));
        }

        return redirect()->route('users.index')
                        ->with('success','L\'utilisateur est créé.');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('users.show',compact('user'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);

        $roles = Role::get();
        $userRole = $user->roles->pluck('name','name')->all();
        $companies = Company::get();
        $jobs = Listing::whereNotNull('job')->where('job','!=', '')->pluck('job', 'job');

        return view('users.edit',compact('user', 'roles', 'userRole', 'companies', 'jobs'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $userCurrentCompany = $user->company_id;


        // if user have not all-access, only roles 2 (User) and/or 3 (admin-company) can be transmit.
        $validRoles = collect([]);
        if (auth()->user()->can('all-access')) {
            $validRoles = Role::pluck('id');
        } else {
            $validRoles = Role::whereIn('id', [2, 3])->pluck('id');
        }

        // validation
        $this->validate($request, [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'company_id' => 'required|exists:companies,id',
            'job' => 'exists:listings,job|nullable',
            'roles' => ['required', 'array', Rule::in($validRoles),
            ],
        ]);

        // verify if checked return 1
        $active = isset($request->active) ? 1 : 0;
        
        $input = $request->all();
        $input['active'] = $active;
        
        // if user have not all-access companies.id  = user.company_id
        if (!auth()->user()->can('all-access')) {
            $input['company_id'] = auth()->user()->company_id;
        }

        // update user 
        $user->update($input);
        
        // assign roles to user (delete and assign)
        DB::table('model_has_roles')->where('model_id',$id)->delete();
        $user->assignRole($request->input('roles'));

        // ---------------- Notification ---------------------
        if($userCurrentCompany !== $user->company->id) {
            // Notify user and admin company of company
            $adminCompany = User::permission('ticket-private')
                ->where('company_id','=', $user->company_id)
                ->get(); 
            // merge to send
            $userAndAdminCompany = collect([$user])->merge($adminCompany);;

            if(env('MAIL_USERNAME')) {
                Notification::send($userAndAdminCompany, new AssignCompanyUser($user));
            }
        }
    
        return redirect()->route('users.index')
                        ->with('success','L\'utilisateur a été mis à jour.');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
                        ->with('success','L\'utilisateur est supprimé.');
    }
}