<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Models\Urgency;
use App\Models\Audit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UrgencyController extends Controller
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

            $urgencies = Urgency::query()->where('is_deleted', false);

            if ($search) {
                $urgencies = $urgencies->where('urgency', 'like', "%{$search}%");
            }

            if ($startDate && $endDate) {
                $urgencies = $urgencies->whereBetween('created_at', [$startDate, $endDate]);
            }

            $urgencies = $urgencies->get();

            return response()->json($urgencies);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Record not found, please try again.'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again.'
            ], 500);
        }
    }

    public function pagination(Request $request)
    {
        //
        try {
            $search = $request->input('search');
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');
            $per_page = $request->input('per_page', 10);

            $urgencies = Urgency::query()->where('is_deleted', false);

            if ($search) {
                $urgencies = $urgencies->where('urgency', 'like', "%{$search}%");
            }

            if ($startDate && $endDate) {
                $urgencies = $urgencies->whereBetween('created_at', [$startDate, $endDate]);
            }

            $urgencies = $urgencies->paginate($per_page);

            return response()->json($urgencies);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Record not found, please try again.'
            ], 404);
        } catch (Exception $e) {
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
        //
        try {
            $validated = $request->validate([
                'urgency' => 'required'
            ]);
            $urgency = Urgency::create([
                'urgency' => $validated['urgency']
            ]);

            Audit::create([
                'category' => 'Urgency',
                'user_id' => Auth::id(),
                'action' => 'Created',
                'data' => json_encode($urgency->toArray()), // ✅ Important
                'description' => 'An Urgency was created by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);

            return response()->json([
                $urgency,
                'message' => 'Urgency created successfully'
            ], 201);
        } catch (Exception $e) {
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
            $urgency = Urgency::where('id', $id)->where('is_deleted', false)->firstOrFail();
            return response()->json([
                $urgency,
                'message' => 'Urgency retrieved successfully'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Record not found, please try again.'
            ], 404);
        } catch (Exception $e) {
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
                'urgency' => 'required'
            ]);
            
            $urgency = Urgency::where('id', $id)->where('is_deleted', false)->firstOrFail();
            
            // Capture the old values before the update
            $oldData = $urgency->toArray();
            
            // Perform the update
            $urgency->update([
                'urgency' => $validated['urgency']
            ]);
            
            // Capture the new values after the update
            $newData = $urgency->fresh()->toArray();
            
            // Create audit log with both old and new data
            Audit::create([
                'category' => 'Urgency',
                'user_id' => Auth::id(),
                'action' => 'Updated',
                'data' => json_encode([
                    'old' => $oldData,
                    'new' => $newData
                ]),
                'description' => 'An Urgency was updated by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);
            
            return response()->json([
                $urgency,
                'message' => 'Urgency updated successfully'
            ], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Record not found, please try again.'
            ], 404);
        } catch (Exception $e) {
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
            $urgency = Urgency::findOrFail($id);
            $urgency->delete();

            Audit::create([
                'category' => 'Urgency',
                'user_id' => Auth::id(),
                'action' => 'Deleted',
                'data' => json_encode($urgency->toArray()),
                'description' => 'An Urgency was deleted by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);
            
            return response()->json([
                $urgency,
                'message' => 'Urgency deleted successfully'
            ], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Record not found, please try again.'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again.'
            ], 500);
        }
    }

    public function archive(string $id)
    {
        //
        try {
            $urgency = Urgency::findOrFail($id);
            $urgency->update([
                'is_deleted' => !$urgency->is_deleted
            ]);

            Audit::create([
                'category' => 'Urgency',
                'user_id' => Auth::id(),
                'action' => 'Soft Deleted',
                'data' => json_encode($urgency->toArray()), // ✅ Important
                'description' => 'An Urgency was soft deleted by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);
            
            return response()->json([
                $urgency,
                'message' => 'Urgency soft deleted successfully'
            ], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Record not found, please try again.'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again.'
            ], 500);
        }
    }
}
