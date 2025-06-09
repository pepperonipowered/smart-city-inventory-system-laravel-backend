<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Barangay;

class BarangaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $barangays = [
            // first list
            // // A Barangays === 11 Barangays
            ['name' => 'A. BONIFACIO-CAGUIOA-RIMANDO (ABCR)', 'latitude' => 16.4188, 'longitude' => 120.5976, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ABANAO-ZANDUETA-KAYONG-CHUGUM-OTEK (AZKCO)', 'latitude' => 16.4136, 'longitude' => 120.5934, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ALFONSO TABORA', 'latitude' => 16.4234, 'longitude' => 120.5969, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'AMBIONG', 'latitude' => 16.4288, 'longitude' => 120.6081, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ANDRES BONIFACIO (LOWER BOKAWKAN)', 'latitude' => 16.4174, 'longitude' => 120.5856, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'LOAKAN APUGAN', 'latitude' => 16.3828, 'longitude' => 120.6246, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ASIN ROAD', 'latitude' => 16.4046, 'longitude' => 120.5636 , 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ATOK TRAIL', 'latitude' => 16.3792, 'longitude' => 120.6307, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'AURORA HILL PROPER', 'latitude' => 16.4245, 'longitude' => 120.6030, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'NORTH CENTRAL AURORA HILL', 'latitude' => 16.4250, 'longitude' => 120.6048, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'SOUTH CENTRAL AURORA HILL', 'latitude' => 16.4254, 'longitude' => 120.6069, 'created_at' => now(), 'updated_at' => now()],
            
            // // B Barangays === 11 Barangays
            // ['name' => 'Bagong Lipunan', 'latitude' => 16.4148, 'longitude' => 120.5952],
            ['name' => 'BAKAKENG CENTRAL', 'latitude' => 16.3960, 'longitude' => 120.5783, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'BAKAKENG NORTE/SUR', 'latitude' => 16.3861, 'longitude' => 120.5909, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'BAL-MARCOVILLE (MARCOVILLE)', 'latitude' => 16.4063, 'longitude' => 120.6035, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'BALSIGAN', 'latitude' => 16.3990, 'longitude' => 120.5937, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'BAYAN PARK EAST', 'latitude' => 16.4271, 'longitude' => 120.6083, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'BAYAN PARK VILLAGE', 'latitude' => 16.4274, 'longitude' => 120.6059, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'WEST BAYAN PARK (LEONILA HILL)', 'latitude' => 16.4285, 'longitude' => 120.6025, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'BGH COMPOUND', 'latitude' => 16.3997, 'longitude' => 120.5975, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'BROOKSIDE', 'latitude' => 16.4210, 'longitude' => 120.6018, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'BROOKSPOINT', 'latitude' => 16.4251, 'longitude' => 120.6091, 'created_at' => now(), 'updated_at' => now()],

            // // C Barangays === 10 Barangays
            ['name' => 'CABINET HILL - TEACHERS CAMP', 'latitude' => 16.4110, 'longitude' => 120.6071, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'CAMDAS SUBDIVISION', 'latitude' => 16.4273, 'longitude' => 120.5927, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'CAMP 7', 'latitude' => 16.3796, 'longitude' => 120.6024, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'CAMP 8', 'latitude' => 16.3978, 'longitude' => 120.6016, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'CAMP ALLEN', 'latitude' => 16.4162, 'longitude' => 120.5912, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'CAMPO FILIPINO', 'latitude' => 16.4155, 'longitude' => 120.5874, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'CITY CAMP CENTRAL', 'latitude' => 16.4106, 'longitude' => 120.5863, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'CITY CAMP PROPER', 'latitude' => 16.4108, 'longitude' => 120.5896, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'COUNTRY CLUB VILLAGE', 'latitude' => 16.4052, 'longitude' => 120.6201, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'CRESENCIA VILLAGE', 'latitude' => 16.4203, 'longitude' => 120.5874, 'created_at' => now(), 'updated_at' => now()],

            // // D Barangays === 6 Barangays
            ['name' => 'LOWER DAGSIAN', 'latitude' => 16.3919, 'longitude' => 120.6081, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'UPPER DAGSIAN', 'latitude' => 16.3946, 'longitude' => 120.6075, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'DIZON SUBDIVISION', 'latitude' => 16.4248, 'longitude' => 120.5899, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'DOMINICAN-MIRADOR', 'latitude' => 16.4067, 'longitude' => 120.5817, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'DONTOGAN', 'latitude' => 16.3818, 'longitude' => 120.5722, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'DPS COMPOUND', 'latitude' => 16.4049, 'longitude' => 120.6049, 'created_at' => now(), 'updated_at' => now()],

            // // E Barangays === 1 Barangay
            ['name' => 'ENGINEERS HILL', 'latitude' => 16.4074, 'longitude' => 120.6021, 'created_at' => now(), 'updated_at' => now()],

            // // F Barangays === 3 Barangays
            ['name' => 'FAIRVIEW', 'latitude' => 16.4176, 'longitude' => 120.5808, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'FERDINAND (CAMPO SIOCO)', 'latitude' => 16.4027, 'longitude' => 120.5915, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'FORT DEL PILAR', 'latitude' => 16.3653, 'longitude' => 120.6177, 'created_at' => now(), 'updated_at' => now()],

            // // G Barangays === 8 Barangays
            ['name' => 'GABRIELA SILANG', 'latitude' => 16.3943, 'longitude' => 120.6041, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'GEFA (LOWER Q.M)', 'latitude' => 16.4080, 'longitude' => 120.5899, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'LOWER GENERAL LUNA', 'latitude' => 16.4150, 'longitude' => 120.5982, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'UPPER GENERAL LUNA', 'latitude' => 16.4132, 'longitude' => 120.6016, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'GIBRALTAR', 'latitude' => 16.4182, 'longitude' => 120.6230, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'GREEN WATER', 'latitude' => 16.4014, 'longitude' => 120.6073, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'GUISAD CENTRAL', 'latitude' => 16.4230, 'longitude' => 120.5855, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'GUISAD SURONG', 'latitude' => 16.4203, 'longitude' => 120.5838, 'created_at' => now(), 'updated_at' => now()],

            // // H Barangays === 7 Barangays
            ['name' => 'HAPPY HOLLOW', 'latitude' => 16.3950, 'longitude' => 120.6238, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'HAPPY HOMES-LUCBAN', 'latitude' => 16.4280, 'longitude' => 120.5956, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'HARRISON-CARRANTES', 'latitude' => 16.4117, 'longitude' => 120.5971, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'HILLSIDE', 'latitude' => 16.3966, 'longitude' => 120.6047, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'HOLYGHOST EXTENSION', 'latitude' => 16.4166, 'longitude' => 120.6040, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'HOLYGHOST PROPER', 'latitude' => 16.4168, 'longitude' => 120.6008, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'HONEYMOON-HOLYGHOST', 'latitude' => 16.4193, 'longitude' => 120.6008, 'created_at' => now(), 'updated_at' => now()],

            // // I Barangays === 3 Barangays
            ['name' => 'IMELDA MARCOS', 'latitude' => 16.4002, 'longitude' => 120.5894, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'IMELDA VILLAGE', 'latitude' => 16.4188, 'longitude' => 120.6056, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'IRISAN', 'latitude' => 16.4203, 'longitude' => 120.5568, 'created_at' => now(), 'updated_at' => now()],

            // // K Barangays === 5 Barangays
            ['name' => 'KABAYANIHAN', 'latitude' => 16.4147, 'longitude' => 120.5969, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'KAGITINGAN', 'latitude' => 16.4160, 'longitude' => 120.5966, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'KAYANG EXTENSION', 'latitude' => 16.4144, 'longitude' => 120.5896, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'KAYANG HILLTOP', 'latitude' => 16.4154, 'longitude' => 120.5942, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'KIAS', 'latitude' => 16.3675, 'longitude' => 120.6316, 'created_at' => now(), 'updated_at' => now()],

            // // L Barangays === 9 Barangays
            ['name' => 'LEGARDA-BURNHAM-KISAD', 'latitude' => 16.4072, 'longitude' => 120.5948, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'LOAKAN LIWANAG', 'latitude' => 16.3861, 'longitude' => 120.6099, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'LOAKAN PROPER', 'latitude' => 16.3761, 'longitude' => 120.6176, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'LOPEZ JAENA', 'latitude' => 16.4259, 'longitude' => 120.6035, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'LOURDES SUBDIVISION EXTENSION', 'latitude' => 16.4121, 'longitude' => 120.5843, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'LOWER LOURDES SUBDIVISION', 'latitude' => 16.4102, 'longitude' => 120.5851, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'LOURDES SUBDIVISION PROPER', 'latitude' => 16.4107, 'longitude' => 120.5828, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'LUALHATI', 'latitude' => 16.4137, 'longitude' => 120.6201, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'LUCNAB', 'latitude' => 16.4048, 'longitude' => 120.6294, 'created_at' => now(), 'updated_at' => now()],

            // // M Barangays === 12 Barangays
            ['name' => 'MAGSAYSAY PRIVATE RD.', 'latitude' => 16.4241, 'longitude' => 120.5934, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'LOWER MAGSAYSAY', 'latitude' => 16.4208, 'longitude' => 120.5928, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'UPPER MAGSAYSAY', 'latitude' => 16.4167, 'longitude' => 120.5956, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'MALCOLM SQUARE', 'latitude' => 16.4139, 'longitude' => 120.5960, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'MANUEL ROXAS', 'latitude' => 16.4150, 'longitude' => 120.6079, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'UPPER MARKET SUBDIVISION', 'latitude' => 16.4167, 'longitude' => 120.5942, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'MIDDLE QUEZON HILL', 'latitude' => 16.4163, 'longitude' => 120.5747, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'MILITARY CUT-OFF', 'latitude' => 16.4037, 'longitude' => 120.6005, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'MINES VIEW PARK', 'latitude' => 16.4240, 'longitude' => 120.6262, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'EAST MODERN SITE', 'latitude' => 16.4219, 'longitude' => 120.6063, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'WEST MODERNSITE', 'latitude' => 16.4228, 'longitude' => 120.6031, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'MRR-QUEEN OF PEACE', 'latitude' => 16.4130, 'longitude' => 120.5871, 'created_at' => now(), 'updated_at' => now()],

            // // N Barangays === 1 Barangay
            ['name' => 'NEW LUCBAN', 'latitude' => 16.4224, 'longitude' => 120.5959, 'created_at' => now(), 'updated_at' => now()],

            // // O Barangays === 1 Barangay
            ['name' => 'OUTLOOK DRIVE', 'latitude' => 16.4110, 'longitude' => 120.6273, 'created_at' => now(), 'updated_at' => now()],

            // // P Barangays === 10 Barangays
            ['name' => 'PACDAL', 'latitude' => 16.4168, 'longitude' => 120.6151, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'PADRE BURGOS', 'latitude' => 16.4199, 'longitude' => 120.5919, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'PADRE ZAMORA', 'latitude' => 16.4184, 'longitude' => 120.5922, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'PALMA-URBANO', 'latitude' => 16.4129, 'longitude' => 120.5894, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'PHIL-AM', 'latitude' => 16.4004, 'longitude' => 120.5940, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'PINGET', 'latitude' => 16.4268, 'longitude' => 120.5852, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'PINSAO PILOT PROJECT', 'latitude' => 16.4276, 'longitude' => 120.5812, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'PINSAO PROPER', 'latitude' => 16.4265, 'longitude' => 120.5738, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'POLIWES', 'latitude' => 16.3960, 'longitude' => 120.5995, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'PUCSUSAN', 'latitude' => 16.4164, 'longitude' => 120.6294, 'created_at' => now(), 'updated_at' => now()],

            // // Q Barangays === 7 Barangays
            ['name' => 'QUEZON HILL PROPER', 'latitude' => 16.4146, 'longitude' => 120.5821, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'UPPER QUEZON HILL', 'latitude' => 16.4167, 'longitude' => 120.5760, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'EAST QUIRINO HILL', 'latitude' => 16.4310, 'longitude' => 120.5934, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'LOWER QUIRINO HILL', 'latitude' => 16.4310, 'longitude' => 120.5934, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'MIDDLE QUIRINO HILL', 'latitude' => 16.4273, 'longitude' => 120.5896, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'WEST QUIRINO HILL', 'latitude' => 16.4308, 'longitude' => 120.5892, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'QUIRINO-MAGSAYSAY (UPPER QM)', 'latitude' => 16.4050, 'longitude' => 120.5899, 'created_at' => now(), 'updated_at' => now()],

            // // R Barangays === 4 Barangay
            ['name' => 'RIZAL MONUMENT', 'latitude' => 16.4128, 'longitude' => 120.5918, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'LOWER ROCK QUARRY', 'latitude' => 16.4091, 'longitude' => 120.5878, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'MIDDLE ROCK QUARRY', 'latitude' => 16.4085, 'longitude' => 120.5859, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'UPPER ROCK QUARRY', 'latitude' => 16.4074, 'longitude' => 120.5877, 'created_at' => now(), 'updated_at' => now()],

            // // S Barangays === 17 Barangay
            ['name' => 'SAINT JOSEPH VILLAGE', 'latitude' => 16.4170, 'longitude' => 120.6106, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'SALUD MITRA', 'latitude' => 16.4110, 'longitude' => 120.6010, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'SAN ANTONIO VILLAGE', 'latitude' => 16.4265, 'longitude' => 120.6052, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'SAN LUIS VILLAGE', 'latitude' => 16.4088, 'longitude' => 120.5728, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'SAN ROQUE VILLAGE', 'latitude' => 16.4117, 'longitude' => 120.5804, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'SAN VICENTE', 'latitude' => 16.3954, 'longitude' => 120.5968, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'NORTH SANITARY CAMP', 'latitude' => 16.4304, 'longitude' => 120.5994, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'SOUTH SANITARY CAMP', 'latitude' => 16.4272, 'longitude' => 120.5967, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'SANTA ESCOLASTICA', 'latitude' => 16.4006, 'longitude' => 120.6039, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'SANTO ROSARIO', 'latitude' => 16.4022, 'longitude' => 120.5851, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'SANTO TOMAS PROPER', 'latitude' => 16.3844, 'longitude' => 120.5806, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'SANTO TOMAS SCHOOL AREA', 'latitude' => 16.3704, 'longitude' => 120.5768, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'SCOUT BARRIO', 'latitude' => 16.3961, 'longitude' => 120.6084, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'SESSION ROAD', 'latitude' => 16.4095, 'longitude' => 120.5992, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'SLAUGHTER HOUSE AREA', 'latitude' => 16.4202, 'longitude' => 120.5942, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'SLU-SVP', 'latitude' => 16.3898, 'longitude' => 120.5900, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'SOUTH DRIVE', 'latitude' => 16.4079, 'longitude' => 120.6082, 'created_at' => now(), 'updated_at' => now()],

            // // T Barangays === 2 Barangay
            ['name' => 'TEODORA ALONZO', 'latitude' => 16.4198, 'longitude' => 120.5966, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'TRANCOVILLE', 'latitude' => 16.4244, 'longitude' => 120.5999, 'created_at' => now(), 'updated_at' => now()],

            // // V Barangays === 1 Barangay
            ['name' => 'VICTORIA VILLAGE', 'latitude' => 16.4142, 'longitude' => 120.5761, 'created_at' => now(), 'updated_at' => now()]

            // //second updated list
            // ['name' => 'A. BONIFACIO-CAGUIOA-RIMANDO (ABCR)', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'ABANAO-ZANDUETA-KAYONG-CHUGUM-OTEK (AZKCO)', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'ALFONSO TABORA', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'AMBIONG', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'ANDRES BONIFACIO (LOWER BOKAWKAN)', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'ASIN ROAD', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'ATOK TRAIL', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'AURORA HILL PROPER', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'BAKAKENG CENTRAL', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'BAKAKENG NORTE/SUR', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'BAL-MARCOVILLE (MARCOVILLE)', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'BALSIGAN', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'BAYAN PARK EAST', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'BAYAN PARK VILLAGE', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'BGH COMPOUND', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'BROOKSIDE', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'BROOKSPOINT', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'CABINET HIIL T. CAMP', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'CAMDAS SUBDIVISION', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'CAMP 7', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'CAMP 8', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'CAMP ALLEN', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'CAMPO FILIPINO', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'CITY CAMP CENTRAL', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'CITY CAMP PROPER', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'COUNTRY CLUB VILLAGE', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'CRESENCIA VILLAGE', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'DIZON SUBDIVISION', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'DOMINICAN-MIRADOR', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'DONTOGAN', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'DPS COMPOUND', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'EAST MODERN SITE', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'EAST QUIRINO HILL', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'ENGINEERS HILL', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'FAIRVIEW', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'FERDINAND', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'FORT DEL PILAR', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'GABRIELA SILANG', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'GEFA (LOWER Q.M)', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'GIBRALTAR', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'GREEN WATER', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'GUISAD CENTRAL', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'GUISAD SURONG', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'HAPPY HOLLOW', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'HAPPY HOMES-LUCBAN', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'HARRISON-CARRANTES', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'HILLSIDE', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'HOLYGHOST EXTENSION', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'HOLYGHOST PROPER', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'HONEYMOON-HOLYGHOST', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'IMELDA MARCOS', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'IMELDA VILLAGE', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'IRISAN', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'KABAYANIHAN', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'KAGITINGAN', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'KAYANG EXTENSION', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'KAYANG HILLTOP', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'KIAS', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'LEGARDA-BURNHAM-KISAD', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'LOAKAN APUGAN', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'LOAKAN LIWANAG', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'LOAKAN PROPER', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'LOPEZ JAENA', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'LOURDES SUBDIVISION EXTENSION', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'LOURDES SUBDIVISION PROPER', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'LOWER DAGSIAN', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'LOWER GENERAL LUNA', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'LOWER LOURDES SUBDIVISION', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'LOWER MAGSAYSAY', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'LOWER QUIRINO HILL', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'LOWER ROCK QUARRY', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'LUALHATI', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'LUCNAB', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'MAGSAYSAY PRIVATE RD.', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'MALCOLM SQUARE', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'MANUEL ROXAS', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'MIDDLE QUEZON HILL', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'MIDDLE QUIRINO HILL', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'MIDDLE ROCK QUARRY', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'MILITARY CUT-OFF', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'MINES VIEW PARK', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'MRR-QUEEN OF PEACE', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'NEW LUCBAN', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'NORTH CENTRAL AURORA HILL', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'NORTH SANITARY CAMP', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'OUTLOOK DRIVE', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'PACDAL', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'PADRE BURGOS', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'PADRE ZAMORA', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'PALMA-URBANO', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'PHIL-AM', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'PINGET', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'PINSAO PILOT PROJECT', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'PINSAO PROPER', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'POLIWES', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'PUCSUSAN', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'QUEZON HILL PROPER', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'QUIRINO-MAGSAYSAY (UPPER QM)', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'RIZAL MONUMENT', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'SAINT JOSEPH VILLAGE', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'SALUD MITRA', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'SAN ANTONIO VILLAGE', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'SAN LUIS VILLAGE', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'SAN ROQUE VILLAGE', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'SAN VICENTE', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'SANTA ESCOLASTICA', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'SANTO ROSARIO', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'SANTO TOMAS PROPER', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'SANTO TOMAS SCHOOL AREA', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'SCOUT BARRIO', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'SESSION ROAD', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'SLAUGHTER HOUSE AREA', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'SLU-SVP', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'SOUTH CENTRAL AURORA HILL', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'SOUTH DRIVE', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'SOUTH SANITARY CAMP', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'TEODORA ALONZO', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'TRANCOVILLE', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'UPPER DAGSIAN', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'UPPER GENERAL LUNA', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'UPPER MAGSAYSAY', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'UPPER MARKET SUBDIVISION', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'UPPER QUEZON HILL', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'UPPER ROCK QUARRY', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'VICTORIA VILLAGE', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'WEST BAYAN PARK', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'WEST MODERNSITE', 'longitude' => 0.0, 'latitude' => 0.0],
            // ['name' => 'WEST QUIRINO HILL', 'longitude' => 0.0, 'latitude' => 0.0]
        ];

        foreach ($barangays as $barangay) {
            Barangay::create($barangay);
        }
    }
}
