<?php

namespace App\Http\Controllers;

use App\Models\SalesRep;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::with('salesRep')->orderBy('name')->paginate(20);

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        $salesReps = SalesRep::where('is_active', true)->orderBy('name')->get();

        return view('users.create', compact('salesReps'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $data['password'] = bcrypt($data['password']);
        User::create($data);

        return redirect()->route('users.index')->with('success', 'User created.');
    }

    public function edit(User $user): View
    {
        $salesReps = SalesRep::where('is_active', true)->orderBy('name')->get();

        return view('users.edit', compact('user', 'salesReps'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $this->validated($request, $user);

        if (! empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User updated.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?User $user = null): array
    {
        $passwordRule = $user ? ['nullable', 'string', 'min:6'] : ['required', 'string', 'min:6'];

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'.($user?->id)],
            'password' => $passwordRule,
            'role' => ['required', 'in:admin,manager,staff,sr'],
            'sales_rep_id' => ['required_if:role,sr', 'nullable', 'exists:sales_reps,id'],
        ]);

        $data['sales_rep_id'] = $data['role'] === 'sr' ? $data['sales_rep_id'] : null;

        return $data;
    }
}
