<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Source;
use App\Models\ActionsTaken;
use App\Models\Incident;
use App\Models\TypeOfAssistance;
use App\Models\Barangay;
use App\Http\Requests\ReportRequest;
use App\Models\Urgency;
use App\Models\Audit;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Events\UpdateReportsData;
use App\Events\DashboardEvents;
use App\Events\UpdateRelatedIncidents;
use App\Events\UpdateAuditsEvent;
use Illuminate\Support\Facades\Log;
use Exception;

class ReportController extends Controller
{
    //
    public function index(Request $request)
    {
        //
        try {
            $search = $request->query('search');
            $startDate = $request->query('startDate');
            $endDate = $request->query('endDate');
            
            $report = Report::query()->with([
                'source:id,sources', 
                'incident:id,type', 
                'actions:id,actions', 
                'assistance:id,assistance', 
                'barangay:id,name,longitude,latitude',
                'urgency:id,urgency'
            ])->where('is_deleted', false);

            if ($search) {
                $report = $report->where(function ($query) use ($search) {
                    $query->where('time_occurred', 'like', "%{$search}%")
                        ->orWhere('date_occurred', 'like', "%{$search}%")
                        ->orWhere('time_arrival_on_site', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('landmark', 'like', "%{$search}%")
                        ->orWhere('longitude', 'like', "%{$search}%")
                        ->orWhere('latitude', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('source', fn($q) => $q->where('sources', 'like', "%{$search}%"))
                        ->orWhereHas('incident', fn($q) => $q->where('type', 'like', "%{$search}%"))
                        ->orWhereHas('barangay', fn($q) => $q->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('actions', fn($q) => $q->where('actions', 'like', "%{$search}%"))
                        ->orWhereHas('assistance', fn($q) => $q->where('assistance', 'like', "%{$search}%"))
                        ->orWhereHas('urgency', fn($q) => $q->where('urgency', 'like', "%{$search}%"));
                });
            }

            if ($startDate && $endDate){
                $report->whereDate('date_occurred', '>=', $startDate)
                      ->whereDate('date_occurred', '<=', $endDate);
            }

            $report = $report->get();
            return response()->json($report, 200);

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
        try {
            $search = $request->query('search');
            $startDate = $request->query('startDate');
            $endDate = $request->query('endDate');
            $per_page = $request->query('per_page', 10);
            $sortSource = $request->query('sortSource');
            $sortAssistance = $request->query('sortAssistance');
            $sortIncident = $request->query('sortIncident');
            $sortActions = $request->query('sortActions');
            $sortUrgency = $request->query('sortUrgency');
            $sortBarangay = $request->query('sortBarangay');
            

            $report = Report::query()->with([
                'source:id,sources', 
                'incident:id,type', 
                'actions:id,actions', 
                'assistance:id,assistance', 
                'barangay:id,name,longitude,latitude',
                'urgency:id,urgency'
            ])->where('is_deleted', false);

            if ($search) {
                $report = $report->where(function ($query) use ($search) {
                    $query->where('time_occurred', 'like', "%{$search}%")
                        ->orWhere('date_occurred', 'like', "%{$search}%")
                        ->orWhere('time_arrival_on_site', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('landmark', 'like', "%{$search}%")
                        ->orWhere('longitude', 'like', "%{$search}%")
                        ->orWhere('latitude', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('source', fn($q) => $q->where('sources', 'like', "%{$search}%"))
                        ->orWhereHas('incident', fn($q) => $q->where('type', 'like', "%{$search}%"))
                        ->orWhereHas('barangay', fn($q) => $q->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('actions', fn($q) => $q->where('actions', 'like', "%{$search}%"))
                        ->orWhereHas('assistance', fn($q) => $q->where('assistance', 'like', "%{$search}%"))
                        ->orWhereHas('urgency', fn($q) => $q->where('urgency', 'like', "%{$search}%"));
                });
            }


            if ($startDate && $endDate){
                $report->whereDate('date_occurred', '>=', $startDate)
                      ->whereDate('date_occurred', '<=', $endDate);
            }

            if ($sortSource) {
                $report = $report->orderBy('source.sources', $sortSource);
            }

            if ($sortAssistance) {
                $report = $report->orderBy('assistance.assistance', $sortAssistance);
            }

            if ($sortIncident) {
                $report = $report->orderBy('incident.type', $sortIncident);
            }

            if ($sortActions) {
                $report = $report->orderBy('actions.actions', $sortActions);
            }

            if ($sortUrgency) {
                $report = $report->orderBy('urgency.urgency', $sortUrgency);
            }

            if ($sortBarangay) {
                $report = $report->orderBy('barangay.name', $sortBarangay);
            }

            $report = $report->orderBy('id', 'desc')->paginate($per_page); // default behavior
            return response()->json($report, 200);
            
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again later.'
            ], 500);
        }
    }

    public function relatedIncidentsPagination(Request $request)
    {
        try {
            $search = $request->query('search');
            $startDate = $request->query('startDate');
            $endDate = $request->query('endDate');
            $per_page = $request->query('per_page', 10);
            $sortSource = $request->query('sortSource');
            $sortAssistance = $request->query('sortAssistance');
            $sortIncident = $request->query('sortIncident');
            $sortActions = $request->query('sortActions');
            $sortUrgency = $request->query('sortUrgency');
            $sortBarangay = $request->query('sortBarangay');
            $barangay_id = $request->query('barangay_id');

            $relatedIncidents = Report::query()->with([
                'source:id,sources', 
                'incident:id,type', 
                'actions:id,actions', 
                'assistance:id,assistance', 
                'barangay:id,name,longitude,latitude',
                'urgency:id,urgency'
            ])->where('is_deleted', false);    
        
            if ($barangay_id) {
                $relatedIncidents = $relatedIncidents->where('barangay_id', $barangay_id);
            }

            if ($search) {
                $relatedIncidents = $relatedIncidents->where(function ($query) use ($search) {
                    $query->where('time_occurred', 'like', "%{$search}%")
                        ->orWhere('date_occurred', 'like', "%{$search}%")
                        ->orWhere('time_arrival_on_site', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('landmark', 'like', "%{$search}%")
                        ->orWhere('longitude', 'like', "%{$search}%")
                        ->orWhere('latitude', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('source', fn($q) => $q->where('sources', 'like', "%{$search}%"))
                        ->orWhereHas('incident', fn($q) => $q->where('type', 'like', "%{$search}%"))
                        ->orWhereHas('actions', fn($q) => $q->where('actions', 'like', "%{$search}%"))
                        ->orWhereHas('assistance', fn($q) => $q->where('assistance', 'like', "%{$search}%"))
                        ->orWhereHas('urgency', fn($q) => $q->where('urgency', 'like', "%{$search}%"));
                });
            }

            if ($sortSource) {
                $relatedIncidents = $relatedIncidents->orderBy('source_id', $sortSource);
            }

            if ($sortAssistance) {
                $relatedIncidents = $relatedIncidents->orderBy('assistance_id', $sortAssistance);
            }

            if ($sortIncident) {
                $relatedIncidents = $relatedIncidents->orderBy('incident_id', $sortIncident);
            }

            if ($sortActions) {
                $relatedIncidents = $relatedIncidents->orderBy('actions_id', $sortActions);
            }

            if ($sortUrgency) {
                $relatedIncidents = $relatedIncidents->orderBy('urgency_id', $sortUrgency);
            }

            if ($startDate && $endDate) {
                $relatedIncidents = $relatedIncidents->whereBetween('date_occurred', [$startDate, $endDate]);
            }

            $relatedIncidents = $relatedIncidents->orderBy('id', 'desc')->paginate($per_page);
            return response()->json($relatedIncidents, 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again later.'
            ], 500);
        }
    }

    public function relatedIncidentsGenerate(Request $request)
    {
        try {
            $search = $request->query('search');
            $startDate = $request->query('startDate');
            $endDate = $request->query('endDate');
            $barangay_id = $request->query('barangay_id');

            $relatedIncidents = Report::query()->with([
                'source:id,sources', 
                'incident:id,type', 
                'actions:id,actions', 
                'assistance:id,assistance', 
                'barangay:id,name,longitude,latitude',
                'urgency:id,urgency'
            ])->where('is_deleted', false)->orderBy('id', 'desc');    
        
            if ($barangay_id) {
                $relatedIncidents = $relatedIncidents->where('barangay_id', $barangay_id);
            }

            if ($search) {
                $relatedIncidents = $relatedIncidents->where(function ($query) use ($search) {
                    $query->where('time_occurred', 'like', "%{$search}%")
                        ->orWhere('date_occurred', 'like', "%{$search}%")
                        ->orWhere('time_arrival_on_site', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('landmark', 'like', "%{$search}%")
                        ->orWhere('longitude', 'like', "%{$search}%")
                        ->orWhere('latitude', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('source', fn($q) => $q->where('sources', 'like', "%{$search}%"))
                        ->orWhereHas('incident', fn($q) => $q->where('type', 'like', "%{$search}%"))
                        ->orWhereHas('actions', fn($q) => $q->where('actions', 'like', "%{$search}%"))
                        ->orWhereHas('assistance', fn($q) => $q->where('assistance', 'like', "%{$search}%"))
                        ->orWhereHas('urgency', fn($q) => $q->where('urgency', 'like', "%{$search}%"));
                });
            }

            if ($startDate && $endDate) {
                $relatedIncidents = $relatedIncidents->whereBetween('date_occurred', [$startDate, $endDate]);
            }

            $relatedIncidents = $relatedIncidents->get();
            return response()->json($relatedIncidents, 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again later.'
            ], 500);
        }
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReportRequest $reportRequest)
    {
        //
        try {
            $reportRequest->validated();

            $report = Report::create([
                'time_occurred' => $reportRequest->time_occurred,
                'date_occurred' => $reportRequest->date_occurred,
                'time_arrival_on_site' => $reportRequest->time_arrival_on_site,
                'name' => Auth::user()->firstName . ' ' . Auth::user()->lastName,
                'landmark' => $reportRequest->landmark,
                'longitude' => $reportRequest->longitude,
                'latitude' => $reportRequest->latitude,
                'source_id' => $reportRequest->source_id,
                'incident_id' => $reportRequest->incident_id,
                'barangay_id' => $reportRequest->barangay_id,
                'actions_id' => $reportRequest->actions_id,
                'assistance_id' => $reportRequest->assistance_id,
                'urgency_id' => $reportRequest->urgency_id,
                'description' => $reportRequest->description,
            ]);

            Audit::create([
                'category' => 'Report',
                'user_id' => Auth::id(),
                'action' => 'Created',
                'data' => json_encode($report->toArray()),
                'description' => 'A Report was created by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);

            broadcast(new UpdateReportsData());
            broadcast(new DashboardEvents());
            $barangay_id = $report->barangay_id;
            broadcast(new UpdateRelatedIncidents($barangay_id));
            broadcast(new UpdateAuditsEvent($report));

            return response()->json([
                'message' => 'Report created successfully!',
                'report' => $report,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again later.'
            ], 500);
        }
    }

    /**
     * Restore the specified resource.
     */
    public function restore(string $id, Request $request)
    {
        try {
            $audit_id = $request->input('audit_id');
            $audit = Audit::findOrFail($audit_id);
            
            // Get existing audit data
            $auditData = json_decode($audit->data, true);
            
            // Check if this is a batch operation
            $isBatchOperation = is_array($auditData) && isset($auditData[0]) && is_array($auditData[0]);
            
            $report = Report::findOrFail($id);

            if (!$report->is_deleted) {
                return response()->json([
                    'error' => 'This report has already been restored.'
                ], 400);
            }
            
            $report->update(['is_deleted' => false]);
            $report->refresh();
            
            if ($isBatchOperation) {
                // For batch operations, update only the specific item in the array
                $auditData = array_map(function($item) use ($id) {
                    if ($item['id'] == $id) {
                        $item['is_restored'] = true;
                        $item['is_deleted'] = false;
                    }
                    return $item;
                }, $auditData);
                
                $audit->update([
                    'data' => json_encode($auditData)
                ]);
            } else {
                // For single item operations
                $audit->update([
                    'is_restored' => true
                ]);
            }

            // Create new audit entry for the restoration
            Audit::create([
                'category' => 'Report',
                'user_id' => Auth::id(),
                'action' => 'Restored',
                'data' => json_encode(array_merge(
                    $report->toArray(),
                    ['is_restored' => true]
                )),
                'description' => 'A Report was restored by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);

            broadcast(new UpdateReportsData());
            broadcast(new DashboardEvents());
            broadcast(new UpdateAuditsEvent($report));

            return response()->json([
                'message' => 'Report restored successfully'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Report not found, please try again later.'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again later.'
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
            $report = Report::with([
                'source:id,sources', 
                'incident:id,type', 
                'actions:id,actions', 
                'assistance:id,assistance', 
                'barangay:id,name,longitude,latitude',
                'urgency:id,urgency'
            ])->findOrFail($id);

            return response()->json($report, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Report not found, please try again later.'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again later.'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $id, ReportRequest $reportRequest)
    {
        //
        try {
            $reportRequest->validated();

            $report = Report::where('id', $id)->where('is_deleted', false)->firstOrFail();

            $oldData = $report->toArray();

            $report->update([
                'time_occurred' => $reportRequest->time_occurred,
                'date_occurred' => $reportRequest->date_occurred,
                'time_arrival_on_site' => $reportRequest->time_arrival_on_site,
                'source_id' => $reportRequest->source_id,
                'incident_id' => $reportRequest->incident_id,
                'barangay_id' => $reportRequest->barangay_id,
                'name' => Auth::user()->firstName . ' ' . Auth::user()->lastName,
                'latitude' => $reportRequest->latitude,
                'longitude' => $reportRequest->longitude,
                'landmark' => $reportRequest->landmark,
                'actions_id' => $reportRequest->actions_id,
                'assistance_id' => $reportRequest->assistance_id,
                'urgency_id' => $reportRequest->urgency_id,
                'description' => $reportRequest->description,
            ]);

            $newData = $report->fresh()->toArray();

            Audit::create([
                'category' => 'Report',
                'user_id' => Auth::id(),
                'action' => 'Updated',
                'data' => json_encode([
                    $oldData,
                    $newData
                ]),
                'description' => 'A Report was updated by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);

            $updatedReport = $report->load([
                'source:id,sources',
                'incident:id,type',
                'actions:id,actions',
                'assistance:id,assistance',
                'barangay:id,name,longitude,latitude',
                'urgency:id,urgency'
            ]);

            broadcast(new UpdateReportsData($updatedReport));
            broadcast(new UpdateAuditsEvent($report));

            return response()->json([
                'message' => 'Report updated successfully',
                'report' => $report
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Report not found, please try again later.'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again later.' . $e->getMessage()
            ], 500);
        }  
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $report = Report::find($id);
            $report->delete();

            Audit::create([
                'category' => 'Report',
                'user_id' => Auth::id(),
                'action' => 'Deleted',
                'data' => json_encode($report->toArray()), // ✅ Important
                'description' => 'A Report was deleted by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);

            broadcast(new UpdateReportsData());
            broadcast(new DashboardEvents());
            broadcast(new UpdateAuditsEvent($report));

            return response()->json([
                'message' => 'Report deleted successfully'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Report not found, please try again later.'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again later.'
            ], 500);
        }
    }

    public function archive(string $id)
    {
        try {
            $report = Report::find($id);
            $report->update([
                'is_deleted' => !$report->is_deleted
            ]);

            $reportData = $report->toArray();
            $reportData['is_restored'] = false;

            Audit::create([
                'category' => 'Report',
                'user_id' => Auth::id(),
                'action' => 'Soft Deleted',
                'data' => json_encode($reportData),
                'description' => 'A Report was soft deleted by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);

            broadcast(new UpdateReportsData());
            broadcast(new DashboardEvents());
            $barangay_id = $report->barangay_id;
            broadcast(new UpdateRelatedIncidents($barangay_id));
            broadcast(new UpdateAuditsEvent($report));

            return response()->json([
                'message' => 'Report soft deleted successfully'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Report not found, please try again later.'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again later.'
            ], 500);
        }
    }

    public function destroyMultiple(Request $request)
    {
        try {
            Log::info($request->all());
            // Get the selected reports from the request
            $selectedReports = $request->input('data'); // Full report objects

            // Initialize an array to store deleted report data
            $deletedReportsData = [];

            // Loop through each report in the selected reports
            foreach ($selectedReports as $reportData) {
                // Find the report by ID and delete it
                $report = Report::findOrFail($reportData['id']);  // Using findOrFail to ensure the report exists
                $report->delete();

                // Add the deleted report data to the array for tracking
                $deletedReportsData[] = $reportData;
            }

            // Track the deletion for all reports outside the loop
            Audit::create([
                'category' => 'Report',
                'user_id' => Auth::id(),
                'action' => 'Multiple Delete',
                'data' => json_encode($deletedReportsData), // Pass the data of all deleted reports
                'description' => 'Multiple reports were deleted by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);

            broadcast(new UpdateReportsData());
            broadcast(new DashboardEvents());
            broadcast(new UpdateAuditsEvent($deletedReportsData));

            return response()->json([
                'message' => 'Reports deleted successfully'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Report not found, please try again later.'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again later.'
            ], 500);
        }
    }

    // public function archiveMultiple(Request $request)
    // {
    //     try {
    //         // Get the selected reports from the request
    //         $selectedReports = $request->input('selectedReportsData');

    //         // Initialize an array to store deleted report data
    //         $deletedReportsData = [];

    //         // Loop through each report in the selected reports
    //         foreach ($selectedReports as $reportData) {
    //             // Find the report by ID and delete it
    //             $report = Report::findOrFail($reportData['id']);
    //             $report->update([
    //                 'is_deleted' => !$report->is_deleted
    //             ]);
    
    //             // Add the deleted report data to the array for tracking
    //             $deletedReportsData[] = $reportData;
    //         }

    //         // Track the deletion for all reports outside the loop
    //         Audit::create([
    //             'category' => 'Report',
    //             'user_id' => Auth::id(),
    //             'action' => 'Multiple Soft Delete',
    //             'data' => json_encode($deletedReportsData), // Pass the data of all deleted reports
    //             'description' => 'Multiple reports were soft deleted by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
    //         ]);

    //         broadcast(new UpdateReportsData());
    //         broadcast(new DashboardEvents());
    //         $barangay_id = $report->barangay_id;
    //         broadcast(new UpdateRelatedIncidents($barangay_id));
    //         broadcast(new UpdateAuditsEvent());

    //         return response()->json([
    //             'message' => 'Reports soft deleted successfully'
    //         ]);
    //     } catch (ModelNotFoundException $e) {
    //         return response()->json([
    //             'error' => 'Report not found, please try again later.'
    //         ], 404);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'error' => 'Something went wrong, please try again later.'
    //         ], 500);
    //     }
    // }
    public function archiveMultiple(Request $request)
    {
        try {
            // Get the selected reports from the request
            $selectedReports = $request->input('selectedReportsData');

            // Initialize an array to store deleted report data
            $deletedReportsData = [];

            // Loop through each report in the selected reports
            foreach ($selectedReports as $reportData) {
                // Find the report by ID and update its status
                $report = Report::findOrFail($reportData['id']);
                
                // Only process if the report is not already deleted
                if (!$report->is_deleted) {
                    $report->update([
                        'is_deleted' => true
                    ]);

                    // Add the deleted report data to the array for tracking
                    $deletedReportsData[] = $reportData;
                }
            }

            // If no reports were processed (all were already deleted)
            if (empty($deletedReportsData)) {
                return response()->json([
                    'error' => 'Selected reports are already deleted.'
                ], 400);
            }

            // Create audit entry for the batch deletion
            Audit::create([
                'category' => 'Report',
                'user_id' => Auth::id(),
                'action' => 'Multiple Soft Delete',
                'data' => json_encode($deletedReportsData),
                'description' => 'Multiple reports were soft deleted by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName . '.',
            ]);

            broadcast(new UpdateReportsData());
            broadcast(new DashboardEvents());
            $barangay_id = $report->barangay_id;
            broadcast(new UpdateRelatedIncidents($barangay_id));
            broadcast(new UpdateAuditsEvent($deletedReportsData));

            return response()->json([
                'message' => 'Reports soft deleted successfully'
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Report not found, please try again later.'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again later. ' . $e->getMessage()
            ], 500);
        }
    }
}
