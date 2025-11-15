<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Afficher la liste des utilisateurs
     */
    public function index()
    {
        $users = User::with('roles')->latest()->paginate(20);
        return view('utilisateurs.index', compact('users'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $roles = Role::all();
        return view('utilisateurs.create', compact('roles'));
    }

    /**
     * Enregistrer un nouvel utilisateur
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'nullable|exists:roles,name'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);

        if ($request->role) {
            $user->assignRole($request->role);
        }

        return redirect()->route('utilisateurs.index')
            ->with('success', 'Utilisateur créé avec succès !');
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(User $utilisateur)
    {
        $roles = Role::all();
        return view('utilisateurs.edit', compact('utilisateur', 'roles'));
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function update(Request $request, User $utilisateur)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $utilisateur->id,
            'password' => 'nullable|min:6|confirmed',
            'role' => 'nullable|exists:roles,name'
        ]);

        $utilisateur->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($request->password) {
            $utilisateur->update([
                'password' => Hash::make($request->password)
            ]);
        }

        if ($request->role) {
            $utilisateur->syncRoles([$request->role]);
        }

        return redirect()->route('utilisateurs.index')
            ->with('success', 'Utilisateur modifié avec succès !');
    }

    /**
     * Supprimer un utilisateur
     */
    public function destroy(User $utilisateur)
    {
        $utilisateur->delete();
        return redirect()->route('utilisateurs.index')
            ->with('success', 'Utilisateur supprimé avec succès !');
    }
}
