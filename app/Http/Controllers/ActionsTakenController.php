<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActionsTaken;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Models\Audit;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ActionsTakenController extends Controller
{
    //
    public function index(Request $request)
    {
        //
        try {
            $search = $request->query('search');
            $startDate = $request->query('startDate');
            $endDate = $request->query('endDate');
            $per_page = $request->query('per_page', 10);

            $actions = ActionsTaken::query()->where('is_deleted', false);

            if ($search) {
                $actions = $actions->where('actions', 'like', "%{$search}%");
            }

            if ($startDate && $endDate) {
                $actions = $actions->whereBetween('created_at', [$startDate, $endDate]);
            }

            $actions = $actions->get();
            return response()->json($actions, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Record not found, please try again.',
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again.',
            ], 500);
        }
    }

    public function paginate(Request $request)
    {
        //
        try {
            $search = $request->query('search');
            $startDate = $request->query('startDate');
            $endDate = $request->query('endDate');
            $per_page = $request->query('per_page', 10);

            $actions = ActionsTaken::query()->where('is_deleted', false);

            if ($search) {
                $actions = $actions->where('actions', 'like', "%{$search}%");
            }

            if ($startDate && $endDate) {
                $actions = $actions->whereBetween('created_at', [$startDate, $endDate]);
            }

            $actions = $actions->paginate($per_page);
            return response()->json($actions, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Record not found, please try again.',
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again.',
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
                'actions' => 'required'
            ]);
            $action = ActionsTaken::create([
                'actions' => $validated['actions']
            ]);

            Audit::create([
                'category' => 'Actions Taken',
                'user_id' => Auth::id(),
                'action' => 'Created',
                'data' => json_encode($action->toArray()),
                'description' => 'An Actions Taken was created by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);

            return response()->json([
                $action,
                'message' => 'Actions Taken created successfully'
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again.',
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
            $action = ActionsTaken::where('id', $id)->where('is_deleted', false)->firstOrFail();
            return response()->json([
                $action,
                'message' => 'Actions Taken retrieved successfully'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Record not found, please try again.',
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
                'actions' => 'required'
            ]);
            $action = ActionsTaken::where('id', $id)->where('is_deleted', false)->firstOrFail();

            $oldData = $action->toArray();

            $action->update([
                'actions' => $validated['actions']
            ]);

            $newData = $action->fresh()->toArray();

            Audit::create([
                'category' => 'Actions Taken',
                'user_id' => Auth::id(),
                'action' => 'Updated',
                'data' => json_encode([
                    'old' => $oldData,
                    'new' => $newData
                ]),
                'description' => 'An Actions Taken was updated by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);

            return response()->json([
                $action,
                'message' => 'Actions Taken updated successfully'
            ], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Record not found, please try again.',
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
            $action = ActionsTaken::findOrFail($id);
            $action->delete();

            Audit::create([
                'category' => 'Actions Taken',
                'user_id' => Auth::id(),
                'action' => 'Deleted',
                'data' => json_encode($action->toArray()),
                'description' => 'An Actions Taken was deleted by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);

            return response()->json([
                $action,
                'message' => 'Actions Taken deleted successfully'
            ], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Record not found, please try again.',
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
            $action = ActionsTaken::findOrFail($id);
            $action->update([
                'is_deleted' => !$action->is_deleted
            ]);

            Audit::create([
                'category' => 'Actions Taken',
                'user_id' => Auth::id(),
                'action' => 'Soft Deleted',
                'data' => json_encode($action->toArray()),
                'description' => 'An Actions Taken was soft deleted by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);

            return response()->json([
                $action,
                'message' => 'Actions Taken soft deleted successfully'
            ], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Record not found, please try again.',
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again.'
            ], 500);
        }
    }
}
