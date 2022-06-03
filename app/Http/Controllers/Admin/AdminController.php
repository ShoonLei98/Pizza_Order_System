<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    // category profile page
    public function profile()
    {
        $id = auth()->user()->id;
        $userData = User::where('id', $id)->first();
        return view('admin.profile.index')->with(['user' => $userData]);
    }

    public function updateProfile($id, Request $request)
    {
        $userData = $this->requestUserData($request);

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'address' => 'required',
            'phone' => 'required',
        ]);

        if($validator->fails()){
            return back()
                    ->withErrors($validator)
                    ->withInput();
        }

        User::where('id', $id)->update($userData);
        return back()->with(['updateSuccess' => 'Updated Successfully...']);
        dd("success");
    }

    public function changePasswordPage()
    {
        return view('admin.profile.changePassword');
    }

    public function changePassword(Request $request, $id)
    {
        $oldPassword = $request->oldPassword;
        $newPassword = $request->newPassword;
        $confirmPassword = $request->confirmPassword;

        // dd($id);
        $validator = Validator::make($request->all(),[
            'oldPassword' => 'required',
            'newPassword' => 'required',
            'confirmPassword' => 'required',
            ]);

        if($validator->fails()){
            return back()
                    ->withErrors($validator)
                    ->withInput();
        }


        // $oldHashPassword = User::select('password')
        //                        ->where('id',$id)->first()->toArray();

        $userData = User::where('id', $id)->first();
        $hashValue = $userData['password'];
        if(Hash::check($oldPassword, $hashValue))
        {
            if($newPassword != $confirmPassword){
                return back()->with(['notSameError' => 'Confirm password must be same with new password!']);
            }
            else{
                if(strlen($newPassword) <= 6 || strlen($confirmPassword) <= 6){
                    return back()->with(['lengthError' => 'Password must be more than 6 characters!!!']);
                }
                else{
                    $hashNewPassword = Hash::make($newPassword);

                    User::where('id', $id)->update(['password' => $hashNewPassword]);
                    return back()->with(['successPassword' => 'Password Changed Success...']);
                }
            }
        }
        else{
            return back()->with(['notMatchError' => 'Do not match with old your password!']);
        }
    }

    private function requestUserData($request)
    {
        return [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address
        ];
    }
}
