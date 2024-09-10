<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ManageUserController extends Controller
{
    public function index()
    {
        $adminCount = User::where('role', 'admin')->count();
        $userCount = User::where('role', 'user')->count();

        return view('manage-users', compact('adminCount', 'userCount'));
    }

    public function getUsers(Request $request)
    {
        if ($request->ajax()) {
            
            $users = User::all();

            return datatables()->of($users)
            ->addColumn('id', function($row){
                return $row->id;
            })
            ->addColumn('name', function($row){
                return $row->name;
            })
            ->addColumn('email', function($row){
                return $row->email;
            })
            ->make(true);
            
        }
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json(['success' => 'User deleted successfully.']);
    }

    public function store(){
        $data = request()->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $data['password'] = bcrypt($data['password']);
        User::create($data);
        return response()->json(['success' => 'User created successfully.']);
    }

    public function edit($id)
    {
        $user = User::find($id);
        return response()->json(['success' => true, 'data' => $user]);
    }

    public function update($id)
    {
        $data = request()->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password'=>'nullable'
        ]);

        $user = User::find($id);
        $user->update($data);
        return response()->json(['success' => 'User updated successfully.']);
    }
}
