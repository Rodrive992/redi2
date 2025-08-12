<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UsuariosController extends Controller
{
    public function index(Request $request)
    {
        // AJAX: cargar un usuario para el modal de edición
        if ($request->wantsJson() && $request->filled('load')) {
            $u = User::findOrFail($request->get('load'));
            return response()->json($u);
        }

        $search = trim($request->get('search'));
        $usuarios = User::query()
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('cuil', 'like', "%{$search}%")
                  ->orWhere('dependencia', 'like', "%{$search}%");
            })
            ->orderBy('dependencia', 'desc')
            ->paginate(20);

        return view('herramientas.usuarios_panel_control', compact('usuarios'));
    }

    public function crear(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->whereNotNull('email')],
            'cuil'        => ['required', 'string', 'max:30', 'unique:users,cuil'],
            'dependencia' => ['required', 'string', 'max:255'],
            'desempenio'  => ['nullable', 'string', 'max:255'],
            'password'    => ['required', 'confirmed', 'min:6'],
            'permiso'  => ['nullable', 'string', 'max:255'],
        ]);

        $user = new User();
        $user->name        = $data['name'];
        $user->email       = $data['email'] ?? null;
        $user->cuil        = $data['cuil'];
        $user->dependencia = $data['dependencia'];
        $user->desempenio  = $data['desempenio'] ?? null;
        $user->permiso = $data['permiso'];
        $user->password    = Hash::make($data['password']);
        $user->save();

        return response()->json(['message' => 'Usuario creado correctamente', 'id' => $user->id], 201);
    }

    public function actualizar(Request $request)
    {
        $request->validate([
            'id'          => ['required', 'integer', 'exists:users,id'],
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['nullable', 'email', 'max:255',
                Rule::unique('users', 'email')->whereNotNull('email')->ignore($request->id)
            ],
            'cuil'        => ['required', 'string', 'max:30', Rule::unique('users', 'cuil')->ignore($request->id)],
            'dependencia' => ['required', 'string', 'max:255'],
            'desempenio'  => ['nullable', 'string', 'max:255'],
            'permiso'  => ['nullable', 'string', 'max:255'],
            'password'    => ['nullable', 'confirmed', 'min:6'],
        ]);

        $user = User::findOrFail($request->id);
        $user->name        = $request->name;
        $user->email       = $request->email ?: null;
        $user->cuil        = $request->cuil;
        $user->dependencia = $request->dependencia;
        $user->desempenio  = $request->desempenio ?: null;
        $user->permiso = $request->permiso;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json(['message' => 'Usuario actualizado correctamente']);
    }

    public function eliminar(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer', 'exists:users,id'],
        ]);

        // Evitar que un usuario se elimine a sí mismo
        if (auth()->check() && (int)$request->id === (int)auth()->id()) {
            return response()->json(['message' => 'No podés eliminar tu propio usuario.'], 422);
        }

        $user = User::findOrFail($request->id);
        $user->delete();

        return response()->json(['message' => 'Usuario eliminado correctamente']);
    }
}