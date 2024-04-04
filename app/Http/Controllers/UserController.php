<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\RentLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function profile()
    {
        $profile = User::find(Auth::user()->id);
        $rentlogs = RentLogs::with('user', 'book')->where('user_id', Auth::user()->id)->get();
        return view('profile', ['profile' => $profile, 'rent_logs' => $rentlogs]);
    }

    public function settings()
    {
        $profile = User::find(Auth::user()->id);
        return view('settings', ['profile' => $profile]);
    }

    public function update(Request $request)
    {
        $newName = '';
        if ($request->file('image')) {
            $extension = $request->file('image')->getClientOriginalExtension();
            $newName = 'user-' . now()->timestamp . '.' . $extension;
            $request->file('image')->move(public_path('users'), $newName);
            //$request->avatar = $newName;

            $request->merge(['avatar' => $newName]);
        }

        $user = User::find(Auth::user()->id);

        $user->update($request->all());

        Session::flash('message', "Settings has been Updated successfully!");

        return redirect()->back()->with('status', 'Settings has been Updated successfully!');

        //return redirect('settings')->with('status', 'Settings has been Updated Successfully!');

    }

    public function usersss(Request $request)
    {
        $keyword = $request->keyword;

        $users = User::where('role_id', '!=' , 1)->where([
            ['status', 'active'],
            ['username', 'LIKE', '%' . $keyword . '%']
        ])
            ->paginate(10);
        return view('user', ['users' => $users]);
    }
    public function index(Request $request)
    {
        $keyword = $request->keyword;

        $users = User::where('role_id', '!=' , 1)->where([
            ['status', 'active'],
            ['username', 'LIKE', '%' . $keyword . '%']
        ])
            ->paginate(10);
        return view('user', ['users' => $users]);
    }

    public function registeredUsers()
    {
        $registedUsers = User::where('role_id', 2)->where('status', 'inactive')->get();
        return view('registered-user', ['registedUsers' => $registedUsers]);
    }

    public function show($slug)
    {
        $user = User::where('slug', $slug)->first();
        $rentlogs = RentLogs::with('user', 'book')->where('user_id', $user->id)->get();
        return view('user-detail', ['user' => $user, 'rent_logs' => $rentlogs]);
    }

    public function approve($slug)
    {
        $user = User::where('slug', $slug)->first();
        $user->status = 'active';
        $user->save();

        return redirect('user-detail/' . $slug)->with('status', 'User Approved Successfully!');
    }

    public function delete($slug)
    {
        $user = User::where('slug', $slug)->first();
        return view('user-delete', ['user' => $user]);
    }

    public function destroy($slug)
    {
        $user = User::where('slug', $slug)->first();
        $user->delete();

        return redirect('usersss')->with('status', 'User Banned Successfully!');
    }

    public function bannedUsers()
    {
        $bannedUsers = User::onlyTrashed()->get();
        return view('user-banned-list', ['bannedUsers' => $bannedUsers]);
    }

    public function restore($slug)
    {
        $user = User::withTrashed()->where('slug', $slug)->first();
        $user->restore();

        return redirect('usersss')->with('status', 'User Restored Successfully!');
    }

    public function permanentDelete($slug)
    {
        $deletedUser = User::withTrashed()->where('slug', $slug)->first();
        $deletedUser->forceDelete();

        if ($deletedUser) {
            Session::flash('status', 'success');
            Session::flash('message', "Ban Permanent User data $deletedUser->name successfully");
        }

        return redirect('/usersss');
    }
}
