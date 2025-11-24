<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function create()
    {
        // pouze role pracovníků
        $roles = [
            UserRole::WORKER,
        ];

        return view('admin.users.create', compact('roles'));
    }
    public function store(Request $request)
{
    $validated = $request->validate([
        'name'    => ['required', 'string', 'max:255'],
        'surname' => ['required', 'string', 'max:255'],
        'email'   => ['required', 'email', 'max:255', 'unique:users,email'],
        'password' => ['required', 'string', 'min:6', 'confirmed'],
        'role'    => ['required', 'in:worker'],
    ]);

    // vytvoreni automaticky
    //  hash pomoci laravel automaticky
    $user = User::create([
        'name'     => $validated['name'],
        'surname'  => $validated['surname'],
        'email'    => $validated['email'],
        'password' => $validated['password'],
        'role'     => $validated['role'],
    ]);

    return redirect()
        ->route('admin.users.index')
        ->with('success', "Uživatel {$user->name} byl vytvořen.");
}
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())
            ->orderBy('id')
            ->paginate(10);


        return view('admin.users.index', compact('users'));
    }
 // ukazat formular
    public function edit(User $user)
    {
        $roles = UserRole::cases();

        return view('admin.users.edit', compact('user', 'roles'));
    }
    // ulozit hodnoty z formulare
    public function update(Request $request, User $user)
{
    $validated = $request->validate([
        'name'    => ['required', 'string', 'max:255'],
        'surname' => ['required', 'string', 'max:255'],
        'email'   => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        'role'    => ['required', 'in:campaign_manager,coordinator,worker,deactivated'],
    ]);

    if ($validated['role'] === 'admin') {
        return back()->withErrors('Nelze přiřadit roli admin.');
    }

    if ($user->role === 'admin') {
        return back()->withErrors('Nelze upravovat administrátora.');
    }

        $oldRole = $user->role->value;

        // AKTIVACE / DEAKTIVACE

        if ($validated['role'] === 'deactivated') {
            $validated['role'] = 'deactivated';
        } else {
            $validated['role'] = $user->getStrongestRole();
        }

        $user->update($validated);

        $newRole = $validated['role'];

   
        if ($oldRole === 'deactivated' && $newRole !== 'deactivated') {
            return redirect()
                ->route('admin.users.index')
                ->with('success', "Uživatel {$user->name} byl aktivován jako {$newRole}.");
        }


    return redirect()->route('admin.users.index')
        ->with('success', 'Uživatel byl upraven.');
}


    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors('Nemůžeš smazat sám sebe');
        }

        $user->delete();

        return back()->with('success', 'Uživatel smazán');
    }
}
