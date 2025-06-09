<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Road;
use App\Models\Inbound;
use App\Models\Outbound;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Events\UpdateTrafficStatus;
use App\Events\TrafficTracking;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Storage;

class RoadController extends Controller
{
    // Configuration
    private const STATUS_COLORS = [1 => 'green', 2 => 'yellow', 3 => 'red'];
    private const DEFAULT_STATUS_ID = 1;

    // Data retrieval
    // Get all roads with traffic info
    public function index()
    {
        try {
            // Get all roads with related data
            $roads = Road::select('roads.*', 'road_type.type_name as road_type_name')
                ->leftJoin('road_type', 'roads.road_type_id', '=', 'road_type.id')
                ->where('roads.is_deleted', 0)
                ->orderBy('roads.created_at', 'desc')
                ->get();

            $inbounds = Inbound::all()->keyBy('road_id');
            $outbounds = Outbound::all()->keyBy('road_id');

            foreach ($roads as $road) {
                $inbound = $inbounds->get($road->id);
                $outbound = $outbounds->get($road->id);

                $road->inbound = $inbound;
                $road->outbound = $outbound;
                $road->geometry = [
                    'type' => 'LineString',
                    'coordinates' => [
                        'inbound' => $inbound && $inbound->coordinates ? json_decode($inbound->coordinates) : [],
                        'outbound' => $outbound && $outbound->coordinates ? json_decode($outbound->coordinates) : []
                    ]
                ];

                $road->inboundColor = $this->getColorFromStatusId($inbound->status_id ?? self::DEFAULT_STATUS_ID);
                $road->outboundColor = $this->getColorFromStatusId($outbound->status_id ?? self::DEFAULT_STATUS_ID);
            }

            return response()->json(['roads' => $roads], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching roads: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Get road types from database
    public function getRoadTypes()
    {
        try {
            $roadTypes = DB::table('road_type')->get();

            return response()->json([
                'success' => true,
                'roadTypes' => $roadTypes
            ]);
        } catch (\Exception $e) {
            Log::error("Error fetching road types: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Get inbound coordinates
    public function getInboundCoordinates(Road $road)
    {
        return $this->getDirectionCoordinates($road, 'inbound');
    }

    // Get outbound coordinates
    public function getOutboundCoordinates(Road $road)
    {
        return $this->getDirectionCoordinates($road, 'outbound');
    }

    // Get coordinates for direction
    private function getDirectionCoordinates(Road $road, $direction)
    {
        try {
            $modelClass = $direction === 'inbound' ? Inbound::class : Outbound::class;
            $record = $modelClass::where('road_id', $road->id)->first();

            $coordinates = null;
            if ($record && $record->coordinates) {
                $coordinates = json_decode($record->coordinates);
            }

            return response()->json([
                'success' => true,
                $direction => [
                    'coordinates' => $coordinates,
                    'status_id' => $record ? $record->status_id : null
                ]
            ]);
        } catch (\Exception $e) {
            Log::error("Error getting $direction coordinates: " . $e->getMessage(), [
                'exception' => $e,
                'road_id' => $road->id
            ]);

            return response()->json([
                'error' => $e->getMessage(),
                'details' => "Failed to retrieve $direction coordinates"
            ], 500);
        }
    }

    // Data modification
    // Create new road
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'road_name' => 'required|string|max:255',
                'road_type_id' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                Log::error('Road creation validation failed', [
                    'errors' => $validator->errors()->toArray()
                ]);
                return response()->json(['error' => $validator->errors()], 422);
            }

            $road = new Road();
            $road->road_name = $request->input('road_name');
            $road->road_type_id = $request->input('road_type_id');

            if ($request->input('image_path') && strpos($request->input('image_path'), 'base64') !== false) {
            try {
                $image_parts = explode(";base64,", $request->input('image_path'));
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                $filename = time() . '_' . uniqid() . '.' . $image_type;
                
                Storage::disk('public')->put('road_images/' . $filename, $image_base64);
                
                $road->image_path = $filename;
            } catch (\Exception $e) {
                Log::error("Error processing image: " . $e->getMessage());
                return response()->json(['error' => 'Failed to process image'], 500);
            }
        }

            $saved = $road->save();


            if ($saved) {
                $inbound = new Inbound();
                $inbound->road_id = $road->id;
                $inbound->status_id = self::DEFAULT_STATUS_ID;
                $inbound->save();

                $outbound = new Outbound();
                $outbound->road_id = $road->id;
                $outbound->status_id = self::DEFAULT_STATUS_ID;
                $outbound->save();
            }

            $freshRoad = Road::find($road->id);
            broadcast(new TrafficTracking($freshRoad->id));
            return response()->json([
                'message' => $saved ? 'Successfully Created' : 'Creation failed',
                'success' => $saved,
                'data' => [
                    'road' => $freshRoad
                ]
            ], $saved ? 201 : 500);
        } catch (\Exception $e) {
            Log::error("Error creating road: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Update road information
    public function update(Request $request, Road $road)
    {
        try {
            $validator = Validator::make($request->all(), [
                'road_name' => 'sometimes|string|max:255',
                'road_type_id' => 'sometimes|numeric'
            ]);

            if ($validator->fails()) {
                Log::error('Road update validation failed', [
                    'errors' => $validator->errors()->toArray()
                ]);
                return response()->json(['error' => $validator->errors()], 422);
            }

            if ($request->has('road_name')) {
                $road->road_name = $request->input('road_name');
            }

            if ($request->has('road_type_id')) {
                $road->road_type_id = $request->input('road_type_id');
            }

            if ($request->input('image_path') && strpos($request->input('image_path'), 'base64') !== false) {
                try {
                    if ($road->image_path) {
                        $oldImagePath = 'road_images/' . $road->image_path;
                        if (Storage::disk('public')->exists($oldImagePath)) {
                            Storage::disk('public')->delete($oldImagePath);
                        }
                    }

                    $image_parts = explode(";base64,", $request->input('image_path'));
                    $image_type_aux = explode("image/", $image_parts[0]);
                    $image_type = $image_type_aux[1];
                    $image_base64 = base64_decode($image_parts[1]);
                    $filename = time() . '_' . uniqid() . '.' . $image_type;
                    
                    Storage::disk('public')->put('road_images/' . $filename, $image_base64);
                    
                    $road->image_path = $filename;
                } catch (\Exception $e) {
                    Log::error("Error processing image: " . $e->getMessage());
                    return response()->json(['error' => 'Failed to process image'], 500);
                }
            }

            broadcast(new TrafficTracking($road->id));
            $saved = $road->save();

            Log::info("Road save result:", [
                'saved' => $saved,
                'updated_at' => $road->updated_at
            ]);

            $freshRoad = Road::find($road->id);

            return response()->json([
                'message' => $saved ? 'Successfully Updated' : 'Update failed',
                'success' => $saved,
                'data' => [
                    'road' => $freshRoad
                ]
            ]);
        } catch (\Exception $e) {
            Log::error("Error updating road: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Update inbound traffic status
    public function updateInbound(Request $request, Road $road)
    {
        $status_id = (int) $request->status_id;
        broadcast(new UpdateTrafficStatus($road->id, $status_id, 'inbound'));
        return $this->updateDirection($request, $road, 'inbound');
    }

    // Update outbound traffic status
    public function updateOutbound(Request $request, Road $road)
    {
        $status_id = (int) $request->status_id;
        broadcast(new UpdateTrafficStatus($road->id, $status_id, 'outbound'));
        return $this->updateDirection($request, $road, 'outbound');
    }

    // Update traffic direction status
    private function updateDirection(Request $request, Road $road, $direction)
    {
        try {
            $status_id = (int) $request->status_id;
            $modelClass = $direction === 'inbound' ? Inbound::class : Outbound::class;

            Log::info("$direction update request", [
                'road_id' => $road->id,
                'status_id' => $status_id
            ]);

            $record = $modelClass::where('road_id', $road->id)->firstOrFail();
            $record->status_id = $status_id;
            $record->save();

            Log::info("$direction update completed", [
                'road_id' => $road->id,
                'new_status_id' => $record->status_id
            ]);

            return response()->json([
                'success' => true,
                $direction => $record->fresh()
            ]);
        } catch (\Exception $e) {
            Log::error("Error updating $direction: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Update inbound coordinates
    public function updateInboundCoordinates(Request $request, Road $road)
    {
        return $this->updateDirectionCoordinates($request, $road, 'inbound');
    }

    // Update outbound coordinates
    public function updateOutboundCoordinates(Request $request, Road $road)
    {
        return $this->updateDirectionCoordinates($request, $road, 'outbound');
    }

    // Update direction coordinates
    private function updateDirectionCoordinates(Request $request, Road $road, $direction)
    {
        try {
            $coordinates = $request->input('coordinates');
            $modelClass = $direction === 'inbound' ? Inbound::class : Outbound::class;

            Log::info("$direction coordinates update request", [
                'road_id' => $road->id,
                'coordinates_type' => gettype($coordinates)
            ]);

            $formattedCoordinates = $this->formatCoordinates($coordinates);
            $record = $this->getOrCreateDirectionRecord($road->id, $modelClass);

            $record->coordinates = $formattedCoordinates;
            $saved = $record->save();

            if (!$saved) {
                throw new \Exception("Failed to save $direction coordinates");
            }

            return response()->json([
                'success' => true,
                $direction => $record->fresh(),
                'message' => "$direction coordinates updated successfully"
            ]);
        } catch (\Exception $e) {
            Log::error("Error updating $direction coordinates: " . $e->getMessage(), [
                'exception' => $e,
                'road_id' => $road->id
            ]);
            return response()->json([
                'error' => $e->getMessage(),
                'details' => 'Failed to update coordinates'
            ], 500);
        }
    }

    // Helper functions
    // Convert status ID to color
    private function getColorFromStatusId($statusId)
    {
        return self::STATUS_COLORS[$statusId] ?? self::STATUS_COLORS[self::DEFAULT_STATUS_ID];
    }

    // Format coordinates for storage
    private function formatCoordinates($coordinates)
    {
        if (is_array($coordinates)) {
            return json_encode($coordinates);
        } elseif (is_string($coordinates)) {
            json_decode($coordinates);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $coordinates;
            }
            throw new \Exception("Invalid coordinates format: not valid JSON");
        }
        throw new \Exception("Invalid coordinates format: " . gettype($coordinates));
    }

    // Get or create direction record
    private function getOrCreateDirectionRecord($roadId, $modelClass)
    {
        $record = $modelClass::where('road_id', $roadId)->first();

        if (!$record) {
            $record = new $modelClass();
            $record->road_id = $roadId;
            $record->status_id = self::DEFAULT_STATUS_ID;
        }

        return $record;
    }

    // delete road 
    public function softDelete(Road $road)
    {
        try {
            $road->is_deleted = 1;
            $road->save();
            broadcast(new TrafficTracking($road->id));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
