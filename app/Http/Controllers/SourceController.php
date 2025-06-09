<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use App\Models\Source;
use App\Models\Audit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SourceController extends Controller
{
    //
    public function index(Request $request)
    {
        //
        try {
            $search = $request->query('search');
            $startDate = $request->query('startDate');
            $endDate = $request->query('endDate');

            $sources = Source::query()->where('is_deleted', false);

            if ($search) {
                $sources = $sources->where('sources', 'like', "%{$search}%");
            }

            if ($startDate && $endDate) {
                $sources = $sources->whereBetween('created_at', [$startDate, $endDate]);
            }

            $sources = $sources->get();
            return response()->json($sources, 200);
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
            $search = $request->query('search');
            $startDate = $request->query('startDate');
            $endDate = $request->query('endDate');
            $per_page = $request->query('per_page', 10);

            $sources = Source::query()->where('is_deleted', false);

            if ($search) {
                $sources->where('sources', 'like', "%{$search}%");
            }

            if ($startDate && $endDate) {
                $sources->whereBetween('created_at', [$startDate, $endDate]);
            }

            $sources = $sources->paginate($per_page);
            return response()->json($sources, 200);
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
                'sources' => 'required'
            ]);
            $source = Source::create([
                'sources' => $validated['sources']
            ]);

            Audit::create([
                'category' => 'Source',
                'user_id' => Auth::id(),
                'action' => 'Created',
                'data' => json_encode($source->toArray()),
                'description' => 'A Source was created by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);

            return response()->json([
                $source,
                'message' => 'Source created successfully'
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
            $source = Source::where('id', $id)->where('is_deleted', false)->firstOrFail();
            return response()->json([
                $source,
                'message' => 'Source retrieved successfully'
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
            $source = Source::where('id', $id)->where('is_deleted', false)->firstOrFail();
            $validated = $request->validate([
                'sources' => 'required'
            ]);

            $oldData = $source->toArray();

            $source->update([
                'sources' => $validated['sources']
            ]);

            $newData = $source->fresh()->toArray();

            Audit::create([
                'category' => 'Source',
                'user_id' => Auth::id(),
                'action' => 'Updated',
                'data' => json_encode([
                    'old' => $oldData,
                    'new' => $newData
                ]),
                'description' => 'A Source was updated by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);

            return response()->json([
                $source,
                'message' => 'Source updated successfully'
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
            $source = Source::findOrFail($id);
            $source->delete();

            Audit::create([
                'category' => 'Source',
                'user_id' => Auth::id(),
                'action' => 'Deleted',
                'data' => json_encode($source->toArray()),
                'description' => 'A Source was deleted by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);

            return response()->json([
                'message' => 'Source deleted successfully'
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
            $source = Source::findOrFail($id);
            $source->update([
                'is_deleted' => !$source->is_deleted
            ]);

            Audit::create([
                'category' => 'Source',
                'user_id' => Auth::id(),
                'action' => 'Soft Deleted',
                'data' => json_encode($source->toArray()),
                'description' => 'A Source was soft deleted by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);

            return response()->json([
                'message' => 'Source soft deleted successfully'
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
