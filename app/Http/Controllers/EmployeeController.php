<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use App\Company;
use Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $employees = Employee::paginate(10);
        foreach ($employees as $employee) {
            $employee->company = $employee->company;
        }
        return view('employees.index', compact(['employees']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $companies = Company::all();
        return view('employees.create', compact(['companies']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        // Name (required), email, logo (minimum 100×100), website
        $validation = validator($request->all(), [
            'first_name' => 'required|min:3|max:50',
            'last_name' => 'required|min:3|max:50',
            'email' => 'email|unique:employees,email',
            'phone' => 'digits:11|unique:employees,phone',
            'company_id'  => 'required|exists:companies,id',
        ]);

        if($validation->fails()){
            return redirect()->route('employees.create')
                    ->withErrors($validation)
                    ->withInput(Input::all());
        }

        $employee = new Employee();
        $employee->first_name = $request->first_name;
        $employee->last_name = $request->last_name;
        $employee->email = $request->email;
        $employee->phone = $request->phone;
        $employee->company_id = $request->company_id;
        $employee->save();

        Session::flash('message', 'Successfully created employee!');

        return redirect()->route('employees.index');
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
        $employee = Employee::find($id);
        $companies = Company::all();

        if($employee){
            return view('employees.edit', compact(['companies', 'employee']));
        }else{
            return redirect()->route('employees.index');
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
        $employee = Employee::find($id);
        if($employee){
            $validation = validator($request->all(), [
                'first_name' => 'required|min:3|max:50',
                'last_name' => 'required|min:3|max:50',
                'phone' => 'digits:11|unique:employees,phone,'.$employee->id,
                'email' => 'email|unique:employees,email,'.$employee->id,
                'company_id'  => 'required|exists:companies,id',
            ]);

            if($validation->fails()){
                return redirect()->route('employee.edit', [$employee->id])
                        ->withErrors($validation)
                        ->withInput(Input::all());
            }

            $employee->first_name = $request->first_name;
            $employee->last_name = $request->last_name;
            $employee->email = $request->email;
            $employee->phone = $request->phone;
            $employee->company_id = $request->company_id;
            $employee->save();

            Session::flash('message', 'Successfully update employee!');

            return redirect()->route('employees.index');
        }else{
            return redirect()->route('employees.index');
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
        $employee = Employee::find($id);

        if ($employee) {
            $employee->delete();
            return [
                'status' => true,
                'msg' => 'Employee deleted',
                'data' => [
                 ]
            ];
        } else {
            return [
                'status' => false,
                'msg' => 'Employee not found',
                'data' => [
                 ]
            ];
        }
    }
}
