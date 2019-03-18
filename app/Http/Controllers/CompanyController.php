<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $companies = Company::paginate(10);
        return view('companies.index', compact(['companies']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('companies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Name (required), email, logo (minimum 100×100), website
        $validation = validator($request->all(), [
            'name' => 'required|min:3|max:50',
            'email' => 'email|unique:companies,email',
            'logo'  => 'image|mimes:jpeg,png,jpg,gif,svg|dimensions:min_width=100,min_height=100',
        ]);

        if($validation->fails()){
            return redirect()->route('companies.create')
                    ->withErrors($validation)
                    ->withInput(Input::all());
        }

        $company = new Company();
        $company->name = $request->name;
        $company->email = $request->email;
        $company->website = $request->website;
        $company->save();

        if($request->hasfile('logo'))
        {
            // $path = $request->file('logo')->store('public');
            $file_name = time().'_'.$company->id.'.'.$request->file('logo')->getClientOriginalExtension();
            $path = $request->file('logo')->storeAs(
                'public', $file_name
            );
            $company->logo = $file_name;
        }
        $company->save();

        Session::flash('message', 'Successfully created company!');

        return redirect()->route('companies.index');
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
        $company = Company::find($id);
        if($company){
            return view('companies.edit', compact(['company']));
        }else{
            return redirect()->route('companies.index');
        }
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
        //
        $company = Company::find($id);
        if($company){
            $validation = validator($request->all(), [
                'name' => 'required|min:3|max:50',
                'email' => 'email|unique:companies,email,'.$company->id,
                'logo'  => 'image|mimes:jpeg,png,jpg,gif,svg|dimensions:min_width=100,min_height=100',
            ]);

            if($validation->fails()){
                return redirect()->route('companies.edit', [$company->id])
                        ->withErrors($validation)
                        ->withInput(Input::all());
            }

            $company->name = $request->name;
            $company->email = $request->email;
            $company->website = $request->website;
            $company->save();

            if($request->hasfile('logo'))
            {
                // $path = $request->file('logo')->store('public');
                if(isset($company->logo)){
                    Storage::delete('public/'.$company->logo);
                }

                $file_name = time().'_'.$company->id.'.'.$request->file('logo')->getClientOriginalExtension();
                $path = $request->file('logo')->storeAs(
                    'public', $file_name
                );
                $company->logo = $file_name;
            }
            $company->save();

            Session::flash('message', 'Successfully update company!');

            return redirect()->route('companies.index');
        }else{
            return redirect()->route('companies.index');
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $company = Company::find($id);

        if ($company) {
            if(isset($company->logo)){
                Storage::delete('public/'.$company->logo);
            }
            $company->delete();
            return [
                'status' => true,
                'msg' => 'Company deleted',
                'data' => [
                 ]
            ];
        } else {
            return [
                'status' => false,
                'msg' => 'Company not found',
                'data' => [
                 ]
            ];
        }
         
    }
}
