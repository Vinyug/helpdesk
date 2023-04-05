<?php
    
namespace App\Http\Controllers;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
        $this->validate($request, [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'company_id' => 'nullable|exists:companies,id',
            'job' => 'exists:listings,job|nullable',
            'roles' => 'required',
        ]);
    
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
    
        // Attribute roles at the field role  
        $role = $this->getModelRoleName($request);
        
        // insert user
        $user = User::create(array_merge($input, compact('role')));
        // assign roles at user
        $user->assignRole($request->input('roles'));
    
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
        $user = User::find($id);
        
        $this->validate($request, [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'company_id' => 'nullable|exists:companies,id',
            'job' => 'exists:listings,job|nullable',
            'roles' => 'required|exists:roles,id'
        ]);

        $input = $request->all();

        // Attribute roles at the field role  
        $role = $this->getModelRoleName($request);
        
        // update user 
        $user->update(array_merge($input, compact('role')));
        
        // assign roles to user (delete and assign)
        DB::table('model_has_roles')->where('model_id',$id)->delete();
        $user->assignRole($request->input('roles'));
    
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

    /**
     * Custom method. 
     *
     */
    public function getModelRoleName(Request $request)
    {
    // get roles of select
    $roles = $request->roles;
    // stock in array
    $roleNames = [];
    
    // for every id of request roles
    foreach($roles as $roleId) {
        $role = Role::find($roleId);
        $roleNames[] = $role->name;
    }
    // separator
    $role = implode(', ', $roleNames);
    
    return $role;
    }
}