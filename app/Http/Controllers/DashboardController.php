<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Observation;
use App\Models\Plant;
use App\Models\Location;

class DashboardController extends Controller
{
    public function index()
    {
        $totalObservations = Observation::count();
        $totalPlants = Plant::count();
        $totalLocations = Location::count();
        return view('dashboard', compact('totalObservations', 'totalPlants', 'totalLocations'));
    }

    public function getActivityLogs(Request $request)
{
    if ($request->ajax()) {
        $audits = \OwenIt\Auditing\Models\Audit::query(); 

        return datatables()->of($audits)
            ->addColumn('ID', function($audit) {
                return $audit->id; // Audit ID
            })
            ->addColumn('User_ID', function($audit) {
                return $audit->user_id; // User who performed the action
            })
            ->addColumn('Event', function($audit) {
                return $audit->event; // The event (created, updated, deleted)
            })
            ->addColumn('Model', function($audit) {
                return class_basename($audit->auditable_type);
            })
            ->addColumn('Model_ID', function($audit) {
                return $audit->auditable_id; // ID of the model audited
            })
            ->addColumn('Old Values', function($audit) {
                return json_encode($audit->old_values); // Old values as JSON
            })
            ->addColumn('New Values', function($audit) {
                return json_encode($audit->new_values); // New values as JSON
            })
            ->addColumn('DateTime', function($audit) {
                return $audit->created_at; // Timestamp of the audit
            })
            ->make(true);
        }
    }
}
