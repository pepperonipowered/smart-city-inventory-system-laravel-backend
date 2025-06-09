<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Report;
use Illuminate\Support\Facades\DB;
use App\Models\Source;
use App\Models\Incident;
use App\Models\Barangay;
use App\Models\ActionsTaken;
use App\Models\TypeOfAssistance;
use App\Models\Urgency;
use App\Models\Audit;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Events\UpdateReportsData;
use App\Events\DashboardEvents;
use App\Events\UpdateAuditsEvent;

class FileUploadController extends Controller
{
    // private function validateAndConvertFields(array $row): array|false
    // {
    //     // Check that date_occurred and description are present
    //     if (empty($row['date_occurred']) || empty($row['description'])) {
    //         return false;
    //     }

    //     // Start by trimming and assigning known fields
    //     $validatedRow = [
    //         'time_occurred' => trim($row['time_occurred'] ?? ''),
    //         'date_occurred' => trim($row['date_occurred']),
    //         'time_arrival_on_site' => trim($row['time_arrival_on_site'] ?? ''),
    //         'name' => trim($row['name'] ?? Auth::user()->firstName . ' ' . Auth::user()->lastName),
    //         'landmark' => trim($row['landmark'] ?? ''),
    //         'longitude' => $row['longitude'] ?? null,
    //         'latitude' => $row['latitude'] ?? null,
    //         'description' => trim($row['description'] ?? ''),
    //     ];

    //     // Fields that should be resolved to IDs
    //     $modelMap = [
    //         'source'     => ['class' => Source::class, 'field' => 'sources'],   // Is 'sources' a column name?
    //         'incident'   => ['class' => Incident::class, 'field' => 'name'],
    //         'barangay'   => ['class' => Barangay::class, 'field' => 'name'],
    //         'actions'    => ['class' => ActionsTaken::class, 'field' => 'name'],
    //         'assistance' => ['class' => TypeOfAssistance::class, 'field' => 'name'],
    //         'urgency'    => ['class' => Urgency::class, 'field' => 'name']
    //     ];
        

    //     foreach ($modelMap as $field => $config) {
    //         if (!isset($row[$field]) || trim($row[$field]) === '') {
    //             return false; // Required foreign key value missing
    //         }

    //         $value = trim($row[$field]);

    //         // If already numeric, assume it's an ID
    //         if (is_numeric($value)) {
    //             $validatedRow[$field] = (int)$value;
    //             continue;
    //         }

    //         try {
    //             $modelClass = $config['class'];
    //             $fieldName = $config['field'];

    //             $record = $modelClass::whereRaw("LOWER({$fieldName}) = ?", [strtolower($value)])->first();

    //             if ($record) {
    //                 $validatedRow[$field] = $record->id;
    //             } else {
    //                 return false; // Invalid value that doesn't match a DB record
    //             }
    //         } catch (\Throwable $e) {
    //             $error = $e->getMessage();
    //             return false;
    //         }
    //     }
    //     return $validatedRow;
    // }
    private function validateAndConvertFields(array $row): array|false
    {
        // Check that required fields are present
        if (empty($row['date_occurred']) || empty($row['description'])) {
            return false;
        }

        // Initialize base row with trimmed values
        $validatedRow = [
            'time_occurred'        => trim($row['time_occurred'] ?? ''),
            'date_occurred'        => trim($row['date_occurred']),
            'time_arrival_on_site' => trim($row['time_arrival_on_site'] ?? ''),
            'name'                 => trim($row['name'] ?? Auth::user()->firstName . ' ' . Auth::user()->lastName),
            'landmark'             => trim($row['landmark'] ?? ''),
            'longitude'            => $row['longitude'] ?? null,
            'latitude'             => $row['latitude'] ?? null,
            'description'          => trim($row['description'] ?? ''),
        ];

        // Maps input fields to their respective model and DB column name
        $modelMap = [
            'source'     => ['class' => \App\Models\Source::class, 'field' => 'sources'],
            'incident'   => ['class' => \App\Models\Incident::class, 'field' => 'name'],
            'barangay'   => ['class' => \App\Models\Barangay::class, 'field' => 'name'],
            'actions'    => ['class' => \App\Models\ActionsTaken::class, 'field' => 'name'],
            'assistance' => ['class' => \App\Models\TypeOfAssistance::class, 'field' => 'name'],
            'urgency'    => ['class' => \App\Models\Urgency::class, 'field' => 'name'],
        ];

        // Whitelisted column names to avoid SQL injection in whereRaw
        $allowedFields = ['name', 'sources'];

        foreach ($modelMap as $field => $config) {
            if (!isset($row[$field]) || trim($row[$field]) === '') {
                return false; // Required foreign key field is missing
            }

            $value = trim($row[$field]);

            // If numeric, assume it's already an ID
            if (is_numeric($value)) {
                $validatedRow[$field] = (int) $value;
                continue;
            }

            try {
                $modelClass = $config['class'];
                $fieldName = $config['field'];

                // Validate the field name against whitelist
                if (!in_array($fieldName, $allowedFields, true)) {
                    throw new \InvalidArgumentException("Invalid field name: {$fieldName}");
                }

                // Use case-insensitive match
                $record = $modelClass::whereRaw("LOWER(`{$fieldName}`) = ?", [strtolower($value)])->first();

                if ($record) {
                    $validatedRow[$field] = $record->id;
                } else {
                    return false; // No matching record found
                }

            } catch (\Throwable $e) {
                // Optional: Log or collect the error if needed
                return false;
            }
        }

        return $validatedRow;
    }



    public function read(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls|max:10240',
            ]);

            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file);
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();

            // Define your header mapping
            $headerMapping = [
                // Excel Header => Your Desired Column Name
                'time_occurred' => 'time_occurred',
                'date_occurred' => 'date_occurred',
                'time_arrival_on_site' => 'time_arrival_on_site',
                'name' => 'name',
                'landmark' => 'landmark',
                'longitude' => 'longitude',
                'latitude' => 'latitude',
                'description' => 'description',
                'source' => 'source',
                'incident' => 'incident',
                'barangay' => 'barangay',
                'actions' => 'actions',
                'assistance' => 'assistance',
                'urgency' => 'urgency'
                // 'source_id' => 'source',
                // 'incident_id' => 'incident',
                // 'barangay_id' => 'barangay',
                // 'actions_id' => 'actions',
                // 'assistance_id' => 'assistance',
                // 'urgency_id' => 'urgency',
            ];

            $headers = array_map(function($header) use ($headerMapping) {
                // Remove any whitespace and special characters from header
                $cleanHeader = trim($header);
                // Return mapped header if exists, otherwise use original (or handle as needed)
                return $headerMapping[$cleanHeader] ?? strtolower(str_replace(' ', '_', $cleanHeader));
            }, $data[0]);

            $rows = array_slice($data, 1);
            $formattedData = [];

            foreach ($rows as $row) {
                // Skip empty rows
                if (!array_filter($row)) {
                    continue;
                }

                // Combine headers with row data
                $rowData = array_combine($headers, $row);
                $formattedData[] = $rowData;
            }

            return response()->json([
                'message' => 'Data successfully fetched from the file',
                'data' => $formattedData,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong, please try again.',
                'error' => $e->getMessage() // For debugging
            ], 500);
        }
    }
    
    
    public function store(Request $request)
    {
        try {
            $request->validate([
                'data' => 'required|array',
            ]);

            $insertedRecords = [];
            $errors = [];
            $failedRows = [];
            $hasValidData = false;

            foreach ($request->input('data') as $index => $row) {
                $rowNumber = $index + 1;

                try {
                    $validatedRow = $this->validateAndConvertFields($row);
                    if (!$validatedRow) {
                        $errorMsg = "Invalid data format or missing required fields";
                        $errors[] = "Row {$rowNumber}: " . $errorMsg;
                        $failedRows[] = [
                            'row_number' => $rowNumber,
                            'data' => $row,
                            'error' => $errorMsg
                        ];
                        continue;
                    }

                    $dateValidation = $this->validateAndFormatDate($validatedRow['date_occurred'], $rowNumber);
                    if (!$dateValidation['success']) {
                        $errors[] = $dateValidation['error'];
                        $failedRows[] = [
                            'row_number' => $rowNumber,
                            'data' => $row,
                            'error' => $dateValidation['error']
                        ];
                        continue;
                    }

                    $report = Report::create([
                        'time_occurred' => $validatedRow['time_occurred'] ?? null,
                        'date_occurred' => $dateValidation['date_occurred'],
                        'time_arrival_on_site' => $validatedRow['time_arrival_on_site'] ?? null,
                        'name' => $validatedRow['name'] ?? null,
                        'landmark' => $validatedRow['landmark'] ?? null,
                        'longitude' => $this->validateCoordinate($validatedRow['longitude'] ?? null, 120.5960, -180, 180),
                        'latitude' => $this->validateCoordinate($validatedRow['latitude'] ?? null, 16.4023, -90, 90),
                        'description' => $validatedRow['description'],
                        'source_id' => $validatedRow['source'] ?? null,
                        'incident_id' => $validatedRow['incident'] ?? null,
                        'barangay_id' => $validatedRow['barangay'] ?? null,
                        'actions_id' => $validatedRow['actions'] ?? null,
                        'assistance_id' => $validatedRow['assistance'] ?? null,
                        'urgency_id' => $validatedRow['urgency'] ?? null,
                    ]);

                    $insertedRecords[] = $report;
                    $hasValidData = true;

                } catch (\Exception $e) {
                    $errorMsg = $e->getMessage();
                    $errors[] = "Row {$rowNumber}: " . $errorMsg;
                    
                    $failedRows[] = [
                        'row_number' => $rowNumber,
                        'data' => $row,
                        'error' => $errorMsg
                    ];
                    continue;
                }
            }

            if (!$hasValidData) {
                return response()->json([
                    'message' => 'No valid data found to import',
                    'errors' => $errors,
                    'failed_rows' => $failedRows
                ], 422);
            }

            // Log successful import
            Audit::create([
                'category' => 'Report',
                'user_id' => Auth::id(),
                'action' => 'Imported',
                'data' => json_encode($insertedRecords),
                'description' => 'Excel File imported by ' . Auth::user()->firstName . ' ' . Auth::user()->lastName .
                    '. Successfully imported ' . count($insertedRecords) . ' records.' .
                    (count($errors) > 0 ? ' ' . count($errors) . ' rows had errors.' : ''),
            ]);

            broadcast(new UpdateReportsData());
            broadcast(new DashboardEvents());
            broadcast(new UpdateAuditsEvent());

            return response()->json([
                'message' => 'Data import completed',
                'errors' => $errors,
                'failed_rows' => $failedRows
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to process the import. Please check the data and try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Validate coordinate value
     */
    private function validateCoordinate($value, $default, $min, $max)
    {
        if (!is_numeric($value)) {
            \Log::warning("Invalid coordinate value: {$value}. Using default: {$default}");
            return $default;
        }

        $value = (float)$value;
        return ($value >= $min && $value <= $max) ? $value : $default;
    }

    /**
     * Validate and format date
     */
    private function validateAndFormatDate($dateString, $rowNumber)
    {
        if (empty($dateString)) {
            return [
                'success' => false,
                'date_occurred' => null,
                'error' => "Row {$rowNumber}: date_occurred is required"
            ];
        }

        $formats = [
            'Y-m-d', 'm/d/Y', 'd/m/Y', 'Y/m/d',
            'm-d-Y', 'd-m-Y', 'Y.m.d', 'd.m.Y',
            'm.d.Y', 'Ymd', 'm/d/y', 'd/m/y',
            'F j, Y', 'M j, Y', 'F j Y',
        ];

        // foreach ($formats as $format) {
        //     $parsedDate = \DateTime::createFromFormat($format, $dateString);
        //     if ($parsedDate !== false) {
        //         return [
        //             'success' => true,
        //             'date_occurred' => $parsedDate->format('Y-m-d'),
        //             'error' => null
        //         ];
        //     }
        // }
        foreach ($formats as $format) {
            $parsedDate = \DateTime::createFromFormat($format, $dateString);
    
            if ($parsedDate !== false) {
                // Fix two-digit year issues
                $year = (int)$parsedDate->format('Y');
                if ($year < 100) {
                    $year += 2000; // Assume 20xx, not 00xx
                    $parsedDate->setDate($year, (int)$parsedDate->format('m'), (int)$parsedDate->format('d'));
                }
    
                return [
                    'success' => true,
                    'date_occurred' => $parsedDate->format('Y-m-d'),
                    'error' => null
                ];
            }
        }

        return [
            'success' => false,
            'date_occurred' => null,
            'error' => "Row {$rowNumber}: Invalid date format for date_occurred. Please use a valid format like YYYY-MM-DD."
        ];
    }
}
