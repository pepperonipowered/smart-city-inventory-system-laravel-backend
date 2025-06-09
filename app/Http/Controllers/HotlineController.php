<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hotline;
use App\Models\Audit;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Events\UpdateContactsEvent;
use App\Events\UpdateAuditsEvent;
use Illuminate\Validation\ValidationException;

class HotlineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        try {
            $search = $request->query('search');
            $startDate = $request->query('startDate');
            $endDate = $request->query('endDate');

            $hotlines = Hotline::query()->where('is_deleted', false);

            if ($search) {
                $hotlines = $hotlines->where('name', 'like', "%{$search}%");
            }

            if ($startDate && $endDate){
                $hotlines->whereDate('created_at', '>=', $startDate)
                      ->whereDate('created_at', '<=', $endDate);
            }

            $hotlines = $hotlines->get();
            return response()->json($hotlines);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Record not found, please try again.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again.'
            ], 500);
        }
    }

    public function pagination(Request $request)
    {
        //
        try {
            $search = $request->query('search');
            $startDate = $request->query('startDate');
            $endDate = $request->query('endDate');
            $per_page = $request->query('per_page', 10);

            $hotlines = Hotline::query()->where('is_deleted', false);

            if ($search) {
                $hotlines = $hotlines->where('name', 'like', "%{$search}%");
            }

            if ($startDate && $endDate){
                $hotlines->whereDate('created_at', '>=', $startDate)
                      ->whereDate('created_at', '<=', $endDate);
            }

            $hotlines = $hotlines->paginate($per_page);
            return response()->json($hotlines);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Record not found, please try again.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again.'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'number' => 'nullable|string|max:25',
                'email' => 'nullable|string|max:40',
                'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($request->hasFile('image_path')) {
                $path = $request->file('image_path')->store('hotlines', 'public');
                $validated['image_path'] = $path;
            }

            $hotline = Hotline::create($validated);

            Audit::create([
                'category' => 'Emergency Contact',
                'user_id' => Auth::id(),
                'action' => 'Created',
                'data' => json_encode($hotline->toArray()),
                'description' => 'A Hotline was created by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);

           broadcast(new UpdateContactsEvent($hotline));
           broadcast(new UpdateAuditsEvent());

            return response()->json([
                'hotline' => $hotline,
                'message' => 'Hotline created successfully'
            ], 201);
        } catch (ValidationException $e) {
            throw $e;
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Record not found, please try again.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        try {
            $hotline = Hotline::where('id', $id)->where('is_deleted', false)->firstOrFail();
            return response()->json($hotline);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Record not found, please try again.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again.'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'number' => 'nullable|string|max:25',
                'email' => 'nullable|string|max:40',
                'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($request->hasFile('image_path')) {
                $path = $request->file('image_path')->store('hotlines', 'public');
                $validated['image_path'] = $path;
            } else {
                unset($validated['image_path']);
            }

            $hotline = Hotline::where('id', $id)->where('is_deleted', false)->firstOrFail();
            
            $oldData = $hotline->toArray();
            
            $hotline->update($validated);

            $newData = $hotline->fresh()->toArray();

            Audit::create([
                'category' => 'Emergency Contact',
                'user_id' => Auth::id(),
                'action' => 'Updated',
                'data' => json_encode([
                    'oldData' => $oldData,
                    'newData' => $newData
                ]),
                'description' => 'A Hotline was updated by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);

            broadcast(new UpdateContactsEvent($hotline));
            broadcast(new UpdateAuditsEvent());

            return response()->json([
                'hotline' => $hotline,
                'message' => 'Hotline updated successfully'
            ], 200);
        } catch (ValidationException $e) {
            throw $e;
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Record not found, please try again.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again.' . $e->getMessage()
            ], 500);
        }
    }

    public function restore(string $id, Request $request)
    {
        //
        try {
            $audit_id = $request->input('audit_id');
            $audit = Audit::findOrFail($audit_id);
            
            $auditData = json_decode($audit->data, true);
            $auditData['is_restored'] = true;
            
            $audit->update([
                'data' => json_encode($auditData),
            ]);

            $hotline = Hotline::findOrFail($id);

            if (!$hotline->is_deleted) {
                return response()->json([
                    'error' => 'This contact has already been restored.'
                ], 400);
            }
            
            $hotline->update([
                'is_deleted' => false
            ]);

            Audit::create([
                'category' => 'Emergency Contact',
                'user_id' => Auth::id(),
                'action' => 'Restored',
                'data' => json_encode($hotline->toArray()),
                'description' => 'A Hotline was restored by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);

            broadcast(new UpdateContactsEvent($hotline));
            broadcast(new UpdateAuditsEvent());

            return response()->json([
                'message' => 'Hotline restored successfully'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Record not found, please try again.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        try {
            $hotline = Hotline::findOrFail($id);
            $hotline->delete();

            Audit::create([
                'category' => 'Emergency Contact',
                'user_id' => Auth::id(),
                'action' => 'Deleted',
                'data' => json_encode($hotline->toArray()),
                'description' => 'A Hotline was deleted by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);

            broadcast(new UpdateContactsEvent($hotline));
            broadcast(new UpdateAuditsEvent());

            return response()->json([
                'message' => 'Hotline deleted successfully'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Record not found, please try again.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again.'
            ], 500);
        }
    }

    public function archive(string $id)
    {
        //
        try {
            $hotline = Hotline::findOrFail($id);
            $hotline->update([
                'is_deleted' => !$hotline->is_deleted
            ]);

            $hotlineData = $hotline->toArray();
            $hotlineData['is_restored'] = false;
            Audit::create([
                'category' => 'Emergency Contact',
                'user_id' => Auth::id(),
                'action' => 'Soft Deleted',
                'data' => json_encode($hotlineData),
                'description' => 'A Hotline was soft deleted by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);

            broadcast(new UpdateContactsEvent($hotline));
            broadcast(new UpdateAuditsEvent());

            return response()->json([
                'message' => 'Hotline soft deleted successfully'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Record not found, please try again.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again.'
            ], 500);
        }
    }
}
