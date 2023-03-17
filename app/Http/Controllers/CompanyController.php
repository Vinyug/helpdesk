<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:company-list|company-create|company-edit|company-delete', ['only' => ['index','show']]);
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
        // get model data with order by latest and paginate 
        $companies = Company::latest()->paginate(5);
        
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
            'city' => 'max:50',
            'zip_code' => 'max:5',
            'siret' => 'digits:14',
            'code_ape' => 'digits:5',
            'phone' => 'digits:10',
            'email' => 'required|email|max:80',
        ]);
        
        // dd($request);

        // insert in DB
        Company::create($request->post());

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
            'city' => 'max:50',
            'zip_code' => 'max:5',
            'siret' => 'digits:14',
            'code_ape' => 'digits:5',
            'phone' => 'digits:10',
            'email' => 'required|email|max:80',
        ]);
        
        $company->fill($request->post())->save();

        return redirect()->route('companies.index')->with('success','L\'entreprise a été mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('companies.index')->with('success','L\'entreprise a été supprimée avec succès');
    }
}
