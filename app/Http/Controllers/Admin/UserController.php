<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    // go to user list
    public function userList()
    {
        $userData = User::where('role', 'user')->paginate(5);
        // dd($userData->toArray());
        return view('admin.user.userList')->with(['user' => $userData]);
    }

    // go to admin list
    public function adminList()
    {
        $userData = User::where('role', 'admin')->paginate(5);
        // dd($userData->toArray());
        return view('admin.user.adminList')->with(['admin' => $userData]);
    }

    // user search
    public function searchUser(Request $request)
    {
        $searchKey = $request->searchUser;
        Session::put('SEARCH_USER',$searchKey);
        $searchData = User::where('role', 'user')
                          ->where(function($query) use($searchKey){
                              $query->orWhere('name', 'like', '%'.$searchKey.'%')
                                    ->orWhere('email', 'like', '%'.$searchKey.'%')
                                    ->orWhere('phone', 'like', '%'.$searchKey.'%')
                                    ->orWhere('address', 'like', '%'.$searchKey.'%');
                          })
                          ->paginate(5);

        $searchData->appends($request->all());
        return view('admin.user.userList')->with(['user' => $searchData]);
    }

    // delete user account
    public function deleteUser($id)
    {
        User::where('id', $id)->delete();
        return back()->with(['deleteSuccess' => 'User account deleted...']);
    }

    // admin account search
    public function searchAdmin(Request $request)
    {
        $searchKey = $request->searchAdmin;
        Session::put('SEARCH_ADMIN', $searchKey);

        $searchData = User::where('role', 'admin')
                          ->where(function($query) use($searchKey){
                              $query->orWhere('name', 'like', '%'.$searchKey.'%')
                                    ->orWhere('email', 'like', '%'.$searchKey.'%')
                                    ->orWhere('phone', 'like', '%'.$searchKey.'%')
                                    ->orWhere('address', 'like', '%'.$searchKey.'%');
                          })->paginate(5);
        $searchData->appends($request->all());
        return view('admin.user.adminList')->with(['admin' => $searchData]);
    }

    // admin account edit page
    public function editAdmin($id)
    {
        $adminData = User::select('*')
                        ->where('id', $id)
                        ->first();
        return view('admin.user.updateAdmin')->with(['admin' => $adminData]);
    }

    // update admin in database
    public function updateAdmin(Request $request, $id)
    {
        // dd($id);
        $userData = $this->requestAdminData($request,$id);
        User::where('id', $id)->update($userData);
        return back()->with(['updateSuccess' => 'Account Updated Successfully...']);
    }

    // delete admin account
    public function deleteAdmin($id)
    {
        User::where('id', $id)->delete();
        return back()->with(['deleteSuccess' => 'Admin account deleted...']);
    }

    private function requestAdminData($request,$id)
    {
        return [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => $request->role,
        ];
    }
}
