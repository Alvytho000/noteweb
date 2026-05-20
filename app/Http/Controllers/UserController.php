<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Log;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil semua user dengan pagination
        $users = $this->userService->getAll();

        return response()->json([
            'status' => 'success',
            'data' => $users,
            'message' => 'Users retrieved successfully'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Return form untuk membuat user baru
        return response()->json([
            'status' => 'success',
            'data' => [
                'name' => '',
                'email' => '',
                'password' => '',
                'role' => 'user' // Default role
            ],
            'message' => 'Create user form structure'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'sometimes|string|in:admin,user'
        ]);

        // Hash password sebelum simpan
        $validatedData['password'] = Hash::make($validatedData['password']);

        // Set default role jika tidak ada
        $validatedData['role'] = $validatedData['role'] ?? 'user';
        $validatedData['email_verified_at'] = Carbon::now();

        // Panggil service untuk create user
        $user = $this->userService->create($validatedData);

        // Hapus password dari response untuk keamanan
        unset($user->password);

        Log::info('User created successfully', ['user_id' => $user->id, 'email' => $user->email]);

        return response()->json([
            'status' => 'success',
            'data' => $user,
            'message' => 'User created successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Pastikan user yang sedang login bisa melihat detailnya sendiri
        if (Auth::id() !== $user->id && Auth::user()->role !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized to view this user'
            ], 403);
        }

        // Hapus password dari response
        unset($user->password);

        return response()->json([
            'status' => 'success',
            'data' => $user,
            'message' => 'User retrieved successfully'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Pastikan user yang sedang login bisa melihat detailnya sendiri
        if (Auth::id() !== $user->id && Auth::user()->role !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized to edit this user'
            ], 403);
        }

        // Hapus password dari response
        unset($user->password);

        return response()->json([
            'status' => 'success',
            'data' => $user,
            'message' => 'User retrieved successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Validasi akses
        if (Auth::id() !== $user->id && Auth::user()->role !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized to update this user'
            ], 403);
        }

        // Validasi input
        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,'.$user->id,
            'password' => 'sometimes|string|min:8|confirmed',
            'role' => 'sometimes|string|in:admin,user'
        ]);

        // Hash password jika ada perubahan password
        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        // Update user via service
        $updatedUser = $this->userService->update($user->id, $validatedData);

        // Hapus password dari response
        unset($updatedUser->password);

        Log::info('User updated successfully', ['user_id' => $user->id]);

        return response()->json([
            'status' => 'success',
            'data' => $updatedUser,
            'message' => 'User updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Tidak bisa menghapus diri sendiri
        if (Auth::id() === $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot delete your own account'
            ], 422);
        }

        // Hanya admin yang bisa menghapus user
        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Only admin can delete users'
            ], 403);
        }

        // Delete user via service
        $this->userService->delete($user->id);

        Log::info('User deleted successfully', ['user_id' => $user->id, 'deleted_by' => Auth::id()]);

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully'
        ], 200);
    }
}
