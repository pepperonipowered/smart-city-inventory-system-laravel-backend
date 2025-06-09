<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Incident;
use App\Models\TypeOfAssistance;
use App\Models\Report;
use App\Models\Source;
use App\Models\Urgency;
use Exception;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Barangay;

class DashboardController extends Controller
{
    public function stackedBarChart(Request $request){
        try {
            $startDate = $request->query('startDate');
            $endDate = $request->query('endDate');

            $stackedBarChart = Report::query()->where('is_deleted', false);

            if ($startDate && $endDate) {
                $stackedBarChart->whereBetween('date_occurred', [$startDate, $endDate]);
            }
            
            $stackedBarChart = $stackedBarChart->with([
                'urgency:id,urgency'
            ])->get(['date_occurred', 'urgency_id']);

            return response()->json(
                $stackedBarChart
            , 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again.'
            ], 500);
        }
    }

    
    public function barChart(Request $request)
    {
        try {
            $startDate = $request->query('startDate');
            $endDate = $request->query('endDate');

            $barChart = Report::query()->where('is_deleted', false);

            if ($startDate && $endDate) {
                $barChart->whereBetween('date_occurred', [$startDate, $endDate]);
            }
            
            $barChart = $barChart->with(['source:id,sources'])
            ->get(['id', 'date_occurred', 'source_id']);

            return response()->json(
                $barChart
            , 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again.'
            ], 500);
        }
    }

    public function totalReport(Request $request)
    {
        try {
            $startDate = $request->query('startDate');
            $endDate = $request->query('endDate');

            $totalReport = Report::query()->where('is_deleted', false);

            if ($startDate && $endDate) {
                $totalReport->whereBetween('date_occurred', [$startDate, $endDate]);
            }
            
            $totalReport = $totalReport->count();

            return response()->json([
                'totalReport' => $totalReport
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again.'
            ], 500);
        }
    }

    public function recent()
    {
        try {
            $recents = Report::with([
                'source:id,sources', 
                'incident:id,type', 
                'actions:id,actions', 
                'assistance:id,assistance', 
                'barangay:id,name,longitude,latitude',
                'urgency:id,urgency'])
                ->latest()
                ->take(5)
                ->get();
            return response()->json([
                'recents' => $recents
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again.'
            ], 500);
        }
    }

    public function pieChart(Request $request){
        try {
            $startDate = $request->query('startDate');
            $endDate = $request->query('endDate');

            $pieChart = Report::query()->where('is_deleted', false);

            if ($startDate && $endDate) {
                $pieChart->whereBetween('date_occurred', [$startDate, $endDate]);
            }
            
            $pieChart = $pieChart->with(['assistance:id,assistance','incident:id,type'])
            ->get(['id', 'date_occurred', 'assistance_id', 'incident_id']);

            return response()->json($pieChart, 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again.'
            ], 500);
        }
    }

    public function heatMap(Request $request){
        try {
            $startDate = $request->query('startDate');
            $endDate = $request->query('endDate');

            $heatMap = Report::query()->where('is_deleted', false);

            if ($startDate && $endDate) {
                $heatMap->whereBetween('date_occurred', [$startDate, $endDate]);
            }

            $heatMap = $heatMap->get(['id','date_occurred']);

            return response()->json($heatMap, 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again.'
            ], 500);
        }
    }

    public function maps(Request $request)
    {
        try {

            $startDate = $request->query('startDate');
            $endDate = $request->query('endDate');

            $barangays = Barangay::withCount(['reports' => function ($query) use ($startDate, $endDate) {
                $query->where('is_deleted', false);
                
                if ($startDate && $endDate) {
                    $query->whereDate('date_occurred', '>=', $startDate)
                          ->whereDate('date_occurred', '<=', $endDate);
                }
            }])
            ->where('is_deleted', false);

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

    public function mostCases(Request $request)
    {
        try {
            $startDate = $request->query('startDate');
            $endDate = $request->query('endDate');

            // $mostCases = $mostCases->orderBy('reports_count', 'desc')->take(3)->get();
            $mostCases = Barangay::withCount(['reports' => function ($query) use ($startDate, $endDate) {
                $query->where('is_deleted', false);
            
                if ($startDate && $endDate) {
                    $query->whereDate('date_occurred', '>=', $startDate)
                          ->whereDate('date_occurred', '<=', $endDate);
                }
            }])
            ->where('is_deleted', false)
            ->orderBy('reports_count', 'desc')
            ->get()->take(3);
            
            return response()->json($mostCases, 200);
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


    public function growth(Request $request){
        try {
            $currentYear = (int) $request->input('currentYear');
            $currentMonth = (int) $request->input('currentMonth');
    
            // Create current and previous month Carbon instances
            $currentDate = Carbon::createFromDate($currentYear, $currentMonth, 1);
            $previousDate = $currentDate->copy()->subMonth();
    
            $currentStart = $currentDate->copy()->startOfMonth();
            $currentEnd = $currentDate->copy()->endOfMonth();
    
            $previousStart = $previousDate->copy()->startOfMonth();
            $previousEnd = $previousDate->copy()->endOfMonth();
    
            // Base query for reports
            $reportQuery = Report::query()->where('is_deleted', false);
    
            // Count reports for current and previous months
            $countCurrent = (clone $reportQuery)->whereBetween('date_occurred', [$currentStart, $currentEnd])->count();
            $countPrevious = (clone $reportQuery)->whereBetween('date_occurred', [$previousStart, $previousEnd])->count();
    
            // Compute percentage change
            $percentageChange = 0;
            if ($countPrevious === 0) {
                $percentageChange = $countCurrent > 0 ? 100 : 0;
            } else {
                $percentageChange = (($countCurrent - $countPrevious) / $countPrevious) * 100;
            }
    
            return response()->json([
                'currentMonth' => $currentDate->format('F Y'),
                'previousMonth' => $previousDate->format('F Y'),
                'percentageChange' => round($percentageChange, 2),
            ], 200);
    
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong, please try again.'
            ], 500);
        }
    }
}
