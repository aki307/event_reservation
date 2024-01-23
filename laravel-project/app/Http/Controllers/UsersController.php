<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Services\GroupService;
use Illuminate\Support\Facades\Log;


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
        $ageSort = $request->query('age_sort', 'none');
        
        $users = $this->userService->getAllUsers($ageSort);
        $groups = $this->groupService->getAllGroups();

        return view('users.index', compact('users', 'groups', 'ageSort'));
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
}
