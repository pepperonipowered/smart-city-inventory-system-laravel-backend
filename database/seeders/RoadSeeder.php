<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Road;
use App\Models\RoadType;

class RoadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roadTypeKeywords = [
            'Intersection' => 'Intersection',
            'Rotunda' => 'Rotunda',
            'Street' => 'Street',
            'Entry Point' => 'Entry Point',
        ];

        $roads = [
            'Naguilian-Bokawkan Intersection',
            'Shuntog-Abanao Intersection',
            'Kayang Street',
            'Shanum Street (O-Shape Intersection)',
            'Abanao-Harrison (O-Shape Intersection)',
            'Kisad Road-BGH (Ina Mansion Intersection)',
            'Kalaw-Harrison Intersection',
            'Harrison Road (DOT Intersection)',
            'Governor Pack Road (DOT Intersection)',
            'Y-Shape Intersection',
            'Bonifacio Rotunda',
            'Gen Luna-Bonifacio Intersection',
            'Cathedral',
            'Session Rotunda',
            'Nevada Rotunda',
            'BGH Rotunda',
            'Sta. Catalina',
            'Mac Doris',
            'Crystal Cave-Kitma-Marcos Highway Intersection',
            'Suello-Marcos Highway Intersection',
            'Military Cut off - Kennon Intersection (BMC)',
            'Pacdal Circle',
            'Teachers Camp',
            'Botanical Garden',
            'Mines View',
            'Stone Kingdom',
            'Lions Head-Kennon Road Entry Point',
            'Camdas Entry Point',
            'Naguilian Entry Point',
        ];

        $roadTypes = RoadType::pluck('id', 'type_name');

        foreach ($roads as $roadName) {
            $matchedType = 'Road';

            foreach ($roadTypeKeywords as $keyword => $typeName) {
                if (stripos($roadName, $keyword) !== false) {
                    $matchedType = $typeName;
                    break;
                }
            }

            $typeId = $roadTypes[$matchedType] ?? $roadTypes['Road'];

            Road::create([
                'road_name' => $roadName,
                'road_type_id' => $typeId,
            ]);
        }
    }
}
