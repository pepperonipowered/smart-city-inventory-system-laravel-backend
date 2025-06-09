<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Audit;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Events\UsersEvent;
use App\Events\UpdateInventoryData;
use App\Events\UpdateAuditsEvent;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {
            $users = User::all(['id', 'firstName', 'middleName', 'lastName', 'email', 'email_verified_at', 'for_911', 'for_inventory', 'for_traffic', 'is_deleted', 'updated_at']);
            return response()->json($users, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Record not found, please try again.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again.',
            ], 500);
        }
    }

    public function usersActive(Request $request)
    {
        try {
            $search = $request->input('search');
            $sortName = $request->input('sortName');
            $sortEmail = $request->input('sortEmail');
            $sortAccess = $request->input('sortAccess');
            $sort911Access = $request->input('sort911Access');
            $per_page = $request->input('per_page', 10);

            $users = User::query()->where('is_deleted', 0);

            if ($search) {
                $users->where(function ($query) use ($search) {
                    $query->where('firstName', 'like', "%{$search}%")
                        ->orWhere('middleName', 'like', "%{$search}%")
                        ->orWhere('lastName', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            if ($sortName) {
                $users->orderBy('firstName', $sortName);
            }

            if ($sortEmail) {
                $users->orderBy('email', $sortEmail);
            }

            if ($sortAccess) {
                $users->orderBy('for_911', $sortAccess);
            }

            if ($sort911Access) {
                $users->orderBy('for_911', $sort911Access);
            }

            $users = $users->paginate($per_page);

            return response()->json($users, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Record not found, please try again.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again.',
            ], 500);
        }
    }

    public function usersArchived(Request $request)
    {
        try {
            $search = $request->input('search');
            $sortName = $request->input('sortName');
            $sortEmail = $request->input('sortEmail');
            $sortAccess = $request->input('sortAccess');
            $sort911Access = $request->input('sort911Access');
            $per_page = $request->input('per_page', 10);

            $users = User::query()->where('is_deleted', 1);

            if ($search) {
                $users->where(function ($query) use ($search) {
                    $query->where('firstName', 'like', "%{$search}%")
                        ->orWhere('middleName', 'like', "%{$search}%")
                        ->orWhere('lastName', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            if ($sortName) {
                $users->orderBy('firstName', $sortName);
            }

            if ($sortEmail) {
                $users->orderBy('email', $sortEmail);
            }

            if ($sortAccess) {
                $users->orderBy('for_911', $sortAccess);
            }

            if ($sort911Access) {
                $users->orderBy('for_911', $sort911Access);
            }

            $users = $users->paginate($per_page);

            return response()->json($users, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Record not found, please try again.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again.',
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function storeUser(Request $request)
    {
        //
        try {
            $request->validate([
                'firstName' => 'required|string|max:50',
                'middleName' => 'nullable|string|max:50',
                'lastName' => 'required|string|max:50',
                'email' => 'required|string|unique:users,email|max:50',
                'password' => 'required|string|confirmed',
                'is_deleted' => 'required|boolean',
                'for_911' => 'required|boolean',
                'for_inventory' => 'required|boolean',
                'for_traffic' => 'required|boolean'
            ]);

            $users = User::create([
                'firstName' => $request->input('firstName'),
                'middleName' => $request->input('middleName'),
                'lastName' => $request->input('lastName'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'is_deleted' => $request->input('is_deleted'),
                'for_911' => $request->input('for_911'),
                'for_inventory' => $request->input('for_inventory'),
                'for_traffic' => $request->input('for_traffic')
            ]);

            broadcast(new UpdateInventoryData('User created: ' . $users->id));

            return response()->json(
                [
                    'message' => 'User created successfully.',
                    'data' => $users,
                ],
                201,
            );
        } catch (\Exception $e) {
            return response()->json(['Store User Error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try {
            $request->validate([
                'firstName' => 'required|string|max:255',
                'middleName' => 'nullable|string|max:255',
                'lastName' => 'required|string|max:255',
                'email' => 'required|string|max:255',
                'password' => 'required|string|confirmed',
                'is_deleted' => 'required|boolean'
            ]);

            $users = User::create([
                'firstName' => $request->input('firstName'),
                'middleName' => $request->input('middleName'),
                'lastName' => $request->input('lastName'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'is_deleted' => $request->input('is_deleted'),
                'email_verified_at' => null
            ]);

            // Send verification email
            $users->sendEmailVerificationNotification();

            return response()->json(
                [
                    'message' => 'User created successfully. Please check your email for verification.',
                    'data' => $users,
                ],
                201,
            );
        } catch (\Exception $e) {
            return response()->json(['Store User Error' => $e->getMessage()], 500);
        }
    }

    public function adminCreate(Request $request)
    {
        //
        try {
            $validated = $request->validate([
                'firstName' => 'required|string|max:255',
                'middleName' => 'nullable|string|max:255',
                'lastName' => 'required|string|max:255',
                'email' => 'required|unique:users,email|string|max:255',
                'password' => 'required|string',
                'for_911' => 'required|boolean',
                'for_inventory' => 'required|boolean',
                'for_traffic' => 'required|boolean',
            ]);

            $users = User::create([
                'firstName' => $validated['firstName'],
                'middleName' => $validated['middleName'],
                'lastName' => $validated['lastName'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'for_911' => $validated['for_911'],
                'for_inventory' => $validated['for_inventory'],
                'for_traffic' => $validated['for_traffic'],
                'is_deleted' => 0,
                'role_id' => 0,
                'email_verified_at' => null
            ]);

            broadcast(new UsersEvent($users));
            broadcast(new UpdateAuditsEvent());
            
            return response()->json(['message' => 'User created successfully.'], 201);
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong, please try again.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function update(Request $request, User $user)
    {
        try {
            $request->validate([
                'firstName' => 'sometimes|required|string|max:255',
                'middleName' => 'sometimes|nullable|string|max:255',
                'lastName' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|string|max:255',
                'password' => 'sometimes|required|string',
                'is_deleted' => 'sometimes|required|boolean',
                'for_911' => 'sometimes|required|boolean',
                'for_inventory' => 'sometimes|required|boolean',

            ]);
            $user->update([
                'firstName' => $request->input('firstName'),
                'middleName' => $request->input('middleName'),
                'lastName' => $request->input('lastName'),
                'email' => $request->input('email'),
                'is_deleted' => $request->input('is_deleted'),
                'for_911' => $request->input('for_911'),
                'for_inventory' => $request->input('for_inventory'),
            ]);

            if ($request->filled('password')) {
                if (!Hash::check($request->input('password'), $user->password)) {
                    $user->password = Hash::make($request->input('password'));
                    $user->save(); // Save user if password is changed
                }
            }

            broadcast(new UpdateInventoryData('User updated: ' . $user->id));

            return response()->json([
                'message' => 'Successfully Updated',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json(['Update User Error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function dashboard(Request $request, string $id)
    {
        try {
            // Validate the incoming request
            $request->validate([
                'for_911' => 'required|boolean', // Ensure for_911 is required and boolean
            ]);
            // Find the resource (row) to update
            $dashboard_role = User::findOrFail($id);
            // Update the specific column (for_911)
            $dashboard_role->for_911 = $request->input('for_911');
            $dashboard_role->save();

            Audit::create([
                'category' => 'User',
                'user_id' => Auth::id(),
                'action' => 'Updated',
                'data' => json_encode($dashboard_role->toArray()), // ✅ Important
                'description' => 'A User was updated by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);

            broadcast(new UsersEvent($dashboard_role));
            broadcast(new UpdateAuditsEvent());

            // Return updated resource
            return response()->json(['message' => 'Role updated successfully', $dashboard_role], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Record not found, please try again.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again.',
            ], 500);
        }
    }

    public function inventory(Request $request, string $id)
    {
        try {
            $request->validate([
                'for_inventory' => 'required|boolean', // Ensure for_inventory is required and boolean
            ]);
            $inventory_role = User::findOrFail($id);
            $inventory_role->for_inventory = $request->input('for_inventory');
            $inventory_role->save();

            Audit::create([
                'category' => 'User',
                'user_id' => Auth::id(),
                'action' => 'Updated',
                'data' => json_encode($inventory_role->toArray()), // ✅ Important
                'description' => 'A User was updated by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);

            broadcast(new UsersEvent($inventory_role));

            // Return updated resource
            return response()->json(['message' => 'Role updated successfully', $inventory_role], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Record not found, please try again.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again.',
            ], 500);
        }
    }

    public function traffic(Request $request, string $id)
    {
        try {
            $request->validate([
                'for_traffic' => 'required|boolean', // Ensure for_inventory is required and boolean
            ]);
            $traffic_role = User::findOrFail($id);
            // Update the specific column (for_inventory)
            $traffic_role->for_traffic = $request->input('for_traffic');
            $traffic_role->save();

            Audit::create([
                'category' => 'User',
                'user_id' => Auth::id(),
                'action' => 'Updated',
                'data' => json_encode($traffic_role->toArray()), // ✅ Important
                'description' => 'A User was updated by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);

            broadcast(new UsersEvent($traffic_role));

            // Return updated resource
            return response()->json(['message' => 'Role updated successfully', $traffic_role], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Record not found, please try again.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again.',
            ], 500);
        }
    }

    public function archive(Request $request, string $id)
    {
        try {
            $request->validate([
                'is_deleted' => 'required|boolean', // Ensure for_911 is required and boolean
            ]);
            $is_deleted = User::findOrFail($id);
            $is_deleted->is_deleted = $request->input('is_deleted');
            $is_deleted->save();

            $new_is_deleted = $is_deleted->is_deleted;

            if ($new_is_deleted) {

                Audit::create([
                    'category' => 'User',
                    'user_id' => Auth::id(),
                    'action' => 'Archived',
                    'data' => json_encode($is_deleted->toArray()),
                    'description' => 'A User was archived by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
                ]);

                broadcast(new UsersEvent($is_deleted));
                broadcast(new UpdateAuditsEvent());

                return response()->json([
                    'message' => 'User archived successfully!, Access to the system is revoked.',
                    'data' => $is_deleted
                ], 200);
            } else {
                Audit::create([
                    'category' => 'User',
                    'user_id' => Auth::id(),
                    'action' => 'Unarchived',
                    'data' => json_encode($is_deleted->toArray()),
                    'description' => 'A User was unarchived by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
                ]);

                broadcast(new UsersEvent($is_deleted));
                broadcast(new UpdateAuditsEvent());

                return response()->json([
                    'message' => 'User unarchived successfully!, Access to the system is granted.',
                    'data' => $is_deleted
                ], 200);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Record not found, please try again.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again.',
            ], 500);
        }
    }

    public function updateUserFor911(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'firstName' => 'required|string|max:255',
                'middleName' => 'nullable|string|max:255',
                'lastName' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
                'old_password' => 'nullable|string',
                'password' => 'nullable|string|confirmed|min:8',
                'password_confirmation' => 'nullable|string|min:8'
            ]);

            // Update basic profile information
            $user->firstName = $validated['firstName'];
            $user->middleName = $validated['middleName'];
            $user->lastName = $validated['lastName'];
            $user->email = $validated['email'];

            // Handle password update if provided
            if ($request->filled('password')) {
                if (!Hash::check($validated['old_password'], $user->password)) {
                    return response()->json([
                        'error' => 'Old password is incorrect.'
                    ], 403);
                }

                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            return response()->json([
                'message' => 'Profile updated successfully',
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update profile: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return response()->json([
                'message' => 'Deleted Successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json(['Destroy User Error' => $e->getMessage()], 500);
        }
    }
}
