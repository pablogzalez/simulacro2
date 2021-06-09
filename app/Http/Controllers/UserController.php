<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Profession;
use App\Skill;
use App\Sortable;
use App\User;
use App\UserFilter;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Sortable $sortable)
    {
        $users = User::query()
            ->with('team', 'skills', 'profile.profession')
            ->withLastLogin()
            ->onlyTrashedIf(request()->routeIs('users.trashed'))
            ->when(request('team'), function ($query, $team) {
                if ($team == 'with_team') {
                    $query->has('team');
                } elseif ($team == 'without_team') {
                    $query->doesntHave('team');
                }
            })
            ->applyFilters()
            ->orderByDesc('created_at')
            ->paginate();

        $sortable->appends($users->parameters());

        return view('users.index', [
            'users' => $users,
            'view' => request()->routeIs('users.trashed') ? 'trash' : 'index',
            'skills' => Skill::orderBy('name')->get(),
            'checkedSkills' => collect(request('skills')),
            'sortable' => $sortable,
        ]);
    }

    public function create()
    {
        return $this->form('users.create', new User);
    }

    public function store(CreateUserRequest $request)
    {
        $request->createUser();

        return redirect()->route('users.index');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return $this->form('users.edit', $user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $request->updateUser($user);

        return redirect()->route('users.show', $user);
    }

    public function destroy($id)
    {
        $user = User::onlyTrashed()->where('id', $id)->firstOrFail();

        $user->forceDelete();

        return redirect()->route('users.trashed');
    }

    public function trash(User $user)
    {
        $user->profile()->delete();
        $user->delete();

        return redirect()->route('users.index');
    }

    public function form($view, User $user)
    {
        return view($view, [
            'professions' => Profession::orderBy('title', 'ASC')->get(),
            'skills' => Skill::orderBy('name', 'ASC')->get(),
            'user' => $user,
        ]);
    }
}
