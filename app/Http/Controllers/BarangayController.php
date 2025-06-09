<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barangay;
use Exception;
use App\Http\Requests\BarangayRequest;
use App\Models\Report;
use Illuminate\Support\Facades\DB;
use App\Models\Audit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Events\UpdateBarangays;
use App\Events\UpdateAuditsEvent;

class BarangayController extends Controller
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

            $barangays = Barangay::where('is_deleted', false);

            $barangays = Barangay::withCount(['reports' => function ($query) use ($startDate, $endDate) {
                $query->where('is_deleted', false);
                
                if ($startDate && $endDate) {
                    $query->whereDate('date_occurred', '>=', $startDate)
                          ->whereDate('date_occurred', '<=', $endDate);
                }
            }])
            ->where('is_deleted', false);

            if ($search) {
                $barangays->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('longitude', 'like', "%{$search}%")
                        ->orWhere('latitude', 'like', "%{$search}%");
                });
            }

            $barangays = $barangays->get();

            return response()->json($barangays, 200);

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

    public function pagination(Request $request)
    {
        //
        try {
            $search = $request->query('search');
            $startDate = $request->query('startDate');
            $endDate = $request->query('endDate');
            $per_page = $request->query('per_page', 10);

            $barangays = Barangay::withCount(['reports' => function ($query) use ($startDate, $endDate) {
                $query->where('is_deleted', false);
                
                if ($startDate && $endDate) {
                    $query->whereDate('date_occurred', '>=', $startDate)
                          ->whereDate('date_occurred', '<=', $endDate);
                }
            }])
            ->where('is_deleted', false);

            if ($search) {
                $barangays->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('longitude', 'like', "%{$search}%")
                        ->orWhere('latitude', 'like', "%{$search}%");
                });
            }

            $barangays = $barangays->paginate($per_page);
            return response()->json($barangays, 200);
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
    public function store(BarangayRequest $barangayRequest)
    {
        //
        try {
            $barangayRequest->validated();

            $barangay = Barangay::create([
                'name' => $barangayRequest->name,
                'longitude' => $barangayRequest->longitude,
                'latitude' => $barangayRequest->latitude,
            ]);

            Audit::create([
                'category' => 'Barangay',
                'user_id' => Auth::id(),
                'action' => 'Created',
                'data' => json_encode($barangay->toArray()),
                'description' => 'A Barangay was created by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);

            broadcast(new UpdateBarangays());
            broadcast(new UpdateAuditsEvent());

            return response()->json([
                'message' => 'Barangay created successfully!',
                'barangay' => $barangay,
            ], 200);
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
            $barangay = Barangay::where('id', $id)->where('is_deleted', false)->firstOrFail();
            return response()->json($barangay, 200);
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
     * Update the specified resource in storage.
     */
    public function update(string $id, BarangayRequest $barangayRequest)
    {
        //
        try {
            $barangayRequest->validated();

            $barangay = Barangay::where('id', $id)->where('is_deleted', false)->firstOrFail();

            $oldData = $barangay->toArray();

            $barangay->update([
                'name' => $barangayRequest->name,
                'longitude' => $barangayRequest->longitude,
                'latitude' => $barangayRequest->latitude
            ]);

            $newData = $barangay->fresh()->toArray();

            Audit::create([
                'category' => 'Barangay',
                'user_id' => Auth::id(),
                'action' => 'Updated',
                'data' => json_encode([
                    'old' => $oldData,
                    'new' => $newData
                ]),
                'description' => 'A Barangay was updated by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);

            broadcast(new UpdateBarangays($barangay));
            broadcast(new UpdateAuditsEvent());

            return response()->json([
                'message' => 'Barangay updated successfully!',
                'barangay' => $barangay,
            ]);
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        try {
            $barangay = Barangay::findOrFail($id);
            $barangay->delete();

            Audit::create([
                'category' => 'Barangay',
                'user_id' => Auth::id(),
                'action' => 'Deleted',
                'data' => json_encode($barangay->toArray()), // ✅ Important
                'description' => 'A Barangay was deleted by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);

            broadcast(new UpdateBarangays());
            broadcast(new UpdateAuditsEvent());

            return response()->json([
                'message' => 'Barangay deleted successfully!',
                'barangay' => $barangay,
            ]);
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

    public function archive(string $id)
    {
        //
        try {
            $barangay = Barangay::findOrFail($id);
            $barangay->update([
                'is_deleted' => !$barangay->is_deleted
            ]);

            Audit::create([
                'category' => 'Barangay',
                'user_id' => Auth::id(),
                'action' => 'Soft Deleted',
                'data' => json_encode($barangay->toArray()), // ✅ Important
                'description' => 'A Barangay was soft deleted by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);

            broadcast(new UpdateBarangays());
            broadcast(new UpdateAuditsEvent());

            return response()->json([
                'message' => 'Barangay soft deleted successfully!',
                'barangay' => $barangay,
            ]);
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
}
