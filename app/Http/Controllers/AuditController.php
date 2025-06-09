<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Audit;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class AuditController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $search = $request->query('search', '');
            $startDate = $request->query('startDate', '');
            $endDate = $request->query('endDate', '');

            $audits = Audit::query();

            if ($search){
                $audits->where('action', 'like', "%{$search}%");
            }

            if ($startDate && $endDate){
                $audits->whereDate('created_at', '>=', $startDate)
                      ->whereDate('created_at', '<=', $endDate);
            }

            $audits = $audits->get();
            return response()->json($audits, 200);
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
        try {
            $search = $request->query('search', '');
            $startDate = $request->query('startDate', '');
            $endDate = $request->query('endDate', '');
            $per_page = $request->query('per_page', 10);

            $audits = Audit::query();

            if ($search){
                $audits->where('action', 'like', "%{$search}%")
                ->orWhere('category', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            }

            if ($startDate && $endDate) {
                $audits->whereDate('created_at', '>=', $startDate)
                      ->whereDate('created_at', '<=', $endDate);
            }

            $audits = $audits->orderBy('id', 'desc')->paginate($per_page);
            return response()->json($audits, 200);
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
