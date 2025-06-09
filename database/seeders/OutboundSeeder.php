<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Outbound;
use App\Models\Road;
use App\Models\StatusTraffic;
use Illuminate\Support\Facades\File;

class OutboundSeeder extends Seeder
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
                $outboundCoords = $feature['geometry']['coordinates']['outbound'] ?? null;

                if ($featureName && $outboundCoords) {
                    $coordinatesByName[$featureName] = $outboundCoords;
                }
            }
        }
        foreach ($roads as $road) {
            $randomStatusId = rand(1, 3);
            $coordinates = null;
            if (isset($coordinatesByName[$road->road_name])) {
                $coordinates = $coordinatesByName[$road->road_name];
            }
            Outbound::create([
                'road_id' => $road->id,
                'status_id' => $randomStatusId,
                'coordinates' => $coordinates ? json_encode($coordinates) : null,
            ]);
        }
    }
}
