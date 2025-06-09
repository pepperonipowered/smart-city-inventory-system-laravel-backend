<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Models\TypeOfAssistance;
use App\Models\Audit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TypeOfAssistanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        try {
            $search = $request->query('search', '');
            $startDate = $request->query('startDate', '');
            $endDate = $request->query('endDate', '');

            $assistance = TypeOfAssistance::query()->where('is_deleted', false);

            if ($search) {
                $assistance->where('assistance', 'like', "%{$search}%");
            }

            if ($startDate && $endDate) {
                $assistance->whereBetween('created_at', [$startDate, $endDate]);
            }

            $assistance = $assistance->get();
            return response()->json($assistance);
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
            $search = $request->query('search', '');
            $startDate = $request->query('startDate', '');
            $endDate = $request->query('endDate', '');
            $per_page = $request->query('per_page', 10);

            $assistance = TypeOfAssistance::query()->where('is_deleted', false);

            if ($search) {
                $assistance->where('assistance', 'like', "%{$search}%");
            }

            if ($startDate && $endDate) {
                $assistance->whereBetween('created_at', [$startDate, $endDate]);
            }

            $assistance = $assistance->paginate($per_page);
            return response()->json($assistance);
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
                'assistance' => 'required'
            ]);

            $assistance = TypeOfAssistance::create([
                'assistance' => $validated['assistance']
            ]);

            Audit::create([
                'category' => 'Assistance',
                'user_id' => Auth::id(),
                'action' => 'Created',
                'data' => json_encode($assistance->toArray()), // ✅ Important
                'description' => 'An Assistance was created by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);

            return response()->json([
                $assistance,
                'message' => 'Assistance created successfully'
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        try {
            $assistance = TypeOfAssistance::where('id', $id)->where('is_deleted', false)->firstOrFail();
            return response()->json($assistance);
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
                'assistance' => 'required'
            ]);
            $assistance = TypeOfAssistance::where('id', $id)->where('is_deleted', false)->firstOrFail();

            // Capture the old values before the update
            $oldData = $assistance->toArray();

            $assistance->update([
                'assistance' => $validated['assistance']
            ]);

            // Capture the new values after the update
            $newData = $assistance->fresh()->toArray();

            Audit::create([
                'category' => 'Assistance',
                'user_id' => Auth::id(),
                'action' => 'Updated',
                'data' => json_encode([
                    'old' => $oldData,
                    'new' => $newData
                ]),
                'description' => 'An Assistance was updated by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);

            return response()->json([
                $assistance,
                'message' => 'Assistance updated successfully'
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
            $assistance = TypeOfAssistance::findOrFail($id);
            $assistance->delete();

            Audit::create([
                'category' => 'Assistance',
                'user_id' => Auth::id(),
                'action' => 'Deleted',
                'data' => json_encode($assistance->toArray()), // ✅ Important
                'description' => 'An Assistance was deleted by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);

            return response()->json([
                'message' => 'Assistance deleted successfully'
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
            $assistance = TypeOfAssistance::findOrFail($id);
            $assistance->update([
                'is_deleted' => !$assistance->is_deleted
            ]);

            Audit::create([
                'category' => 'Assistance',
                'user_id' => Auth::id(),
                'action' => 'Soft Deleted',
                'data' => json_encode($assistance->toArray()), // ✅ Important
                'description' => 'An Assistance was soft deleted by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);

            return response()->json([
                'message' => 'Assistance soft deleted successfully'
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
