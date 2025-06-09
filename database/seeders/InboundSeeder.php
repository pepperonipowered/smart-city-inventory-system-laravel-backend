<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inbound;
use App\Models\Road;
use App\Models\StatusTraffic;
use Illuminate\Support\Facades\File;

class InboundSeeder extends Seeder
{
    public function run(): void
    {
        $roads = Road::all();
        
        $coordinatesPath = base_path('public/assets/coordinates.json');
        $coordinatesData = [];
        
        if (File::exists($coordinatesPath)) {
            $jsonContent = File::get($coordinatesPath);
            $coordinatesData = json_decode($jsonContent, true);
        }
        
        $coordinatesByName = [];
        if (!empty($coordinatesData['features'])) {
            foreach ($coordinatesData['features'] as $feature) {
                $featureName = $feature['properties']['name'] ?? '';
                $inboundCoords = $feature['geometry']['coordinates']['inbound'] ?? null;
                
                if ($featureName && $inboundCoords) {
                    $coordinatesByName[$featureName] = $inboundCoords;
                }
            }
        }

        foreach ($roads as $road) {
            $randomStatusId = rand(1, 3);
            $coordinates = null;
            if (isset($coordinatesByName[$road->road_name])) {
                $coordinates = $coordinatesByName[$road->road_name];
            }
            
            Inbound::create([
                'road_id' => $road->id,
                'status_id' => $randomStatusId, 
                'coordinates' => $coordinates ? json_encode($coordinates) : null,
            ]);
        }
    }
}