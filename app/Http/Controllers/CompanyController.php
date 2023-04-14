<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:company-list', ['only' => ['index','show']]);
         $this->middleware('permission:company-create', ['only' => ['create','store']]);
         $this->middleware('permission:company-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:company-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // don't need with powergrid
        // get model data with order by latest and paginate 
        $companies = Company::get();
        
        return view('companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // get all users
        $users = User::all();

        return view('companies.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // data validation
        $request->validate([
            'name' => 'required|max:50',
            'address' => 'required|max:80',
            'city' => 'required|max:50',
            'zip_code' => 'required|digits:5',
            'siret' => 'digits:14|nullable',
            'code_ape' => 'max:5|nullable',
            'phone' => 'digits:10|nullable',
            'email' => 'required|unique:companies,email|max:80',
        ]);
        
        // dd($request);
        
        // genererate uuid
        $uuid = Str::uuid()->toString();

        // verify if checked return 1
        $active = !isset($request->active) ? 0 : 1;
        
        $input = $request->all();
        $input['active'] = $active;
            
        // create company 
        Company::create(array_merge($input, ['uuid' => $uuid]));


        // redirect with message
        return redirect()->route('companies.index')->with('success','L\'entreprise a été enregistrée avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        return view('companies.show',compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        // get all users
        $users = User::all();
        
        return view('companies.edit',compact('company', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|max:50',
            'address' => 'required|max:80',
            'city' => 'required|max:50',
            'zip_code' => 'required|digits:5',
            'siret' => 'digits:14|nullable',
            'code_ape' => 'size:5|nullable',
            'phone' => 'digits:10|nullable',
            'email' => ['required', 'email', Rule::unique('companies')->ignore($company->id)],
        ]);
        
        // verify if checked return 1
        $active = isset($request->active) ? 1 : 0;
        
        $input = $request->all();
        $input['active'] = $active;
  
        // udpate user
        // update company 
        $company->update($input);
        
        // if value of active company change, every users of company take value of active company
        if ($active !== $company->active) {
            $company->users()->update(['active' => $active]);
        }

        $success = 'L\'entreprise a été mise à jour avec succès.';

        if (auth()->user()->can('company-list')) {
            return redirect()->route('companies.index')->with('success', $success);
        }
        
        return redirect()->back()->with('status', $success);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        if(!$company->users()->exists()) {
            $company->delete();
            return redirect()->route('companies.index')->with('success','L\'entreprise a été supprimée avec succès');
        }

        return redirect()->back()->with('status','Vous ne pouvez pas supprimer l\'entreprise, elle possède encore des employés');
    }
}
