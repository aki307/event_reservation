<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;


class UsersController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $users = $this->userService->getAllUsers();
        $groups = $this->userService->getAllGroups();

        return view('users.index', compact('users', 'groups'));
    }

    public function show($id)
    {
        $user = $this->userService->getUserById($id);
        $groups = $this->userService->getAllGroups();

        return view('users.show', compact('user', 'groups'));
    }

    public function edit($id)
    {
        $user = $this->userService->getUserById($id);
        $groups = $this->userService->getAllGroups();
        $userTypes = $this->userService->getAllUserTypes();

        return view('users.edit', compact('user', 'groups', 'userTypes'));
    }

    public function update(UpdateUserRequest $request,  $id)
    {
        try {
            $user = $this->userService->updateUser($request->validated(), $id);
            return redirect()->route('users.show', ['user' => $user->id]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['custom_error' => $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        $this->userService->deleteUser($id);

        return redirect()->route('users.index');
    }
}
