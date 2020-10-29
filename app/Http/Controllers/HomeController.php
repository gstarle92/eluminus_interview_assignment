<?php

namespace App\Http\Controllers;

use App\User;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        return view('home');
    }



    public function add()
    {
        return view('add');
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);
        return redirect()->route('home')
            ->with('success', 'User created successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $user = User::where('id', $id)->first();
        if ($user) {

            $user->delete();
            return redirect()->route('home')
                ->with('success', 'User deleted successfully');
        }
        return redirect()->back()
            ->with('error', 'Opps!! Something went wrong');
    }
    public function edit(Request $request, $id)
    {
        $user = User::where('id', $id)->first();
        if ($user) {

            return view('users.edit', ['user' => $user->toArray()]);
        }
        return redirect()->back()
            ->with('error', 'Opps!! Something went wrong');
    }

    public function update(Request $request)
    {
        // dd($request);
        $user = User::where('id', $request['edit_id'])->first();

        if ($user) {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255'],
                'status' => ['required'],
            ]);


            $user->update([
                'name' => $request['name'],
                'email' => $request['email'],
                'status' => $request['status'],
            ]);
            return redirect()->route('home')
                ->with('success', 'User Updated successfully');
        }
        return redirect()->back()
            ->with('error', 'Opps!! Something went wrong');
    }
    public function getusers(Request $request)
    {
        if ($request->ajax()) {
            $data = User::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="user/edit/' . $row['id'] . '" class="edit btn btn-primary btn-sm " style="background: blue;">Edit</a>&nbsp;&nbsp;<a href="javascript:void(0)"   id="' . $row['id'] . '" class="confirmDelete delete btn btn-primary btn-sm" style="background: red;">Delete</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
}
