<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Incident;
use App\Models\TypeOfAssistance;
use App\Models\Report;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Audit;
use Illuminate\Support\Facades\Auth;

class IncidentController extends Controller
{
    //
    public function index(Request $request)
    {
        //
        try {
            $search = $request->query('search', '');
            $startDate = $request->query('startDate', '');
            $endDate = $request->query('endDate', '');

            $incident = Incident::query()->where('is_deleted', false);

            if ($search) {
                $incident = $incident->where('type', 'like', "%{$search}%");
            }

            if ($startDate && $endDate) {
                $incident = $incident->whereBetween('created_at', [$startDate, $endDate]);
            }

            $incident = $incident->get();
            return response()->json($incident, 200);
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

    public function paginate(Request $request)
    {
        //
        try {
            $search = $request->query('search', '');
            $startDate = $request->query('startDate', '');
            $endDate = $request->query('endDate', '');
            $per_page = $request->query('per_page', 10);

            $incident = Incident::query()->where('is_deleted', false);

            if ($search) {
                $incident = $incident->where('type', 'like', "%{$search}%");
            }

            if ($startDate && $endDate) {
                $incident = $incident->whereBetween('created_at', [$startDate, $endDate]);
            }

            $incident = $incident->paginate($per_page);
            return response()->json($incident, 200);
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
                'type' => 'required'
            ]);
            $incident = Incident::create([
                'type' => $validated['type']
            ]);

            Audit::create([
                'category' => 'Incident',
                'user_id' => Auth::id(),
                'action' => 'Created',
                'data' => json_encode($incident->toArray()),
                'description' => 'An Incident was created by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);

            return response()->json([
                $incident,
                'message' => 'Incident created successfully'
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
            $incident = Incident::where('id', $id)->where('is_deleted', false)->firstOrFail();
            return response()->json([
                $incident,
                'message' => 'Incident retrieved successfully'
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
                'type' => 'required'
            ]);
            $incident = Incident::where('id', $id)->where('is_deleted', false)->firstOrFail();

            $oldData = $incident->toArray();

            $incident->update([
                'type' => $validated['type']
            ]);

            $newData = $incident->fresh()->toArray();

            Audit::create([
                'category' => 'Incident',
                'user_id' => Auth::id(),
                'action' => 'Updated',
                'data' => json_encode([
                    'oldData' => $oldData,
                    'newData' => $newData
                ]),
                'description' => 'An Incident was updated by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);

            return response()->json([
                $incident,
                'message' => 'Incident updated successfully'
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        try {
            $incident = Incident::findOrFail($id);
            $incident->delete();

            Audit::create([
                'category' => 'Incident',
                'user_id' => Auth::id(),
                'action' => 'Deleted',
                'data' => json_encode($incident->toArray()),
                'description' => 'An Incident was deleted by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);

            return response()->json([
                $incident,
                'message' => 'Incident deleted successfully'
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

    public function archive(string $id)
    {
        //
        try {
            $incident = Incident::findOrFail($id);
            $incident->update([
                'is_deleted' => !$incident->is_deleted
            ]);

            Audit::create([
                'category' => 'Incident',
                'user_id' => Auth::id(),
                'action' => 'Soft Deleted',
                'data' => json_encode($incident->toArray()),
                'description' => 'An Incident was soft deleted by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);

            return response()->json([
                $incident,
                'message' => 'Incident soft deleted successfully'
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
}
