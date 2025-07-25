<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        
        return view('user::index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('user::create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:100',
            'role' => 'required|in:admin,manager,user',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->input('is_active') === '1';

        User::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'Usuário criado com sucesso!');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        return view('user::show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('user::edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
            'position' => ['nullable', 'string', 'max:100'],
            'role' => ['required', 'in:admin,manager,user'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->input('is_active') === '1';

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent admin from deleting themselves
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Você não pode deletar sua própria conta!');
        }

        // Prevent deletion of the last admin
        if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return redirect()->route('users.index')
                ->with('error', 'Não é possível deletar o último administrador do sistema!');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Usuário deletado com sucesso!');
    }
} 