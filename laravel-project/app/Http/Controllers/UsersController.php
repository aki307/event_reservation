<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Services\GroupService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use App\Models\Group;
use App\Models\UserType;

class UsersController extends Controller
{
    protected $userService;
    protected $groupService;

    public function __construct(UserService $userService, GroupService $groupService)
    {
        $this->userService = $userService;
        $this->groupService = $groupService;
    }

    public function index(Request $request)
    {

        $users = $this->userService->getAllUsers($request);
        $groups = $this->groupService->getAllPosts();
        return view('users.index', compact('users', 'groups'));
    }

    public function show($id)
    {
        $user = $this->userService->getUserById($id);
        $groups = $this->groupService->getAllGroups();

        return view('users.show', compact('user', 'groups'));
    }

    public function edit($id)
    {
        $user = $this->userService->getUserById($id);
        $groups = $this->groupService->getAllGroups();
        $userTypes = $this->userService->getAllUserTypes();

        return view('users.edit', compact('user', 'groups', 'userTypes'));
    }

    public function update(UpdateUserRequest $request,  $id)
    {
        try {
            $user = $this->userService->updateUser($request->validated(), $id);
            return redirect()->route('users.show', ['user' => $user->id]);
        } catch (\Exception $e) {
            Log::error("User update failed: " . $e->getMessage(), ['user_id' => $id]);
            return redirect()->back()->withErrors(['custom_error' => $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $this->userService->deleteUser($id);
            return redirect()->route('users.index');
        } catch (\Exception $e) {
            Log::error("User deletion failed: " . $e->getMessage(), ['user_id' => $id]);
            return redirect()->back()->withErrors(['custom_error' => $e->getMessage()]);
        }
    }

    public function exportCsv()
    {
        $fileName = 'users.csv';
        $users = User::all();
        $groups = Group::all();
        $userTypes = UserType::all();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = ['ID', 'ユーザ名', '性別', '生年月日', '役職', 'ユーザタイプ', 'ログインID'];

        $callback = function () use ($users, $columns, $groups, $userTypes) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($users as $user) {
                $gender = config('gender.types.' . $user->gender);
                $group = $groups->firstWhere('id', $user->group_id)->name;
                $group = config('groups.types.' . $group);
                $userTypeName = optional($userTypes->firstWhere('id', $user->user_type_id))->name;
                $userType = config('user_types.types.' . $userTypeName);

                $row = [
                    $user->id,
                    $user->user_name,
                    $gender,
                    $user->dob,
                    $group,
                    $userType,
                    $user->login_id,
                ];

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
