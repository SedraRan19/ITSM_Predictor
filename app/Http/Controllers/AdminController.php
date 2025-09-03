<?php

namespace App\Http\Controllers;

use App\Models\{Incident,Ml_prediction_historie,Service_desk,Prediction};
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;

class AdminController extends Controller
{
    protected static $username = "AIData";
    protected static $password = "267A#j+]dBCPtbO<ZVf)8GQs92UBJ2kYxYE={stzN6OS9MDrWtM6BypYcVDm;XrHgCm;vn2RYNnky-OT-)[G,O;R>-ie[#Wkqz>>";


    public function add_incident(){
        $serviceDesks = Service_desk::all();
        return view('addIncident',compact('serviceDesks'));
    }

    public function display_incident($id){
        $incident = Incident::findOrFail($id); 
        return view('displayIncident',compact('incident'));
    }

    public function incident_update(Request $request,$id){
        $incident = Incident::findOrFail($id);

        $oldCategory = $incident->predict_category;

        $incident->predict_category = $request->input('predict_category');
        $incident->incident = $request->input('incidentType');
        $incident->save();

        Ml_prediction_historie::create([
            'incident_id'   => $incident->id,
            'input_text'    => $incident->description, // or short_description if you prefer
            'predicted_label' => $oldCategory,
            'confidence'    => $request->input('confidence', null), // if available
            'model_used'    => 'TicketCategorizer-v1', // put your model name
            'algorithm'     => 'ML/DL', // specify algorithm used
            'predicted_at'  => Carbon::now(),
            'triggered_by'  => null, // user who updated
            'is_correct'    => $oldCategory === $incident->predict_category, // check if prediction was correct
            'actual_label'  => $incident->predict_category,
        ]);

        return redirect()->back()->with('success', 'Incident updated successfully!');
    }
    
    public function generateAll(Request $request){
        $incidentIds = explode(',', $request->input('incident_ids', ''));

        $fileName = 'incidents_export_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $response = new StreamedResponse(function () use ($incidentIds) {
            $handle = fopen('php://output', 'w');

            // CSV header
            fputcsv($handle, [
                'Number',
                'Requested For',
                'Priority',
                'Service Desk',
                'Assignment Group',
                'Short Description',
                'Description',
                'Category Prediction',
                'Type of Ticket',
            ]);

            // Fetch incidents by IDs
            Incident::whereIn('id', $incidentIds)
                ->chunk(200, function ($incidents) use ($handle) {
                    foreach ($incidents as $incident) {
                        fputcsv($handle, [
                            $incident->number ?? '',
                            $incident->requested_for ?? '',
                            $incident->priority ?? '',
                            $incident->service_desk ?? '',
                            $incident->assignment_group ?? '',
                            $incident->short_description ?? '',
                            $incident->description ?? '',
                            $incident->predict_category ?? '',
                            $incident->incident == 0 ? 'Request' : 'Incident',
                        ]);
                    }
                });

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        return $response;
    }
    public function index_template() {
        $serviceDesks = Service_desk::all();
        $incidents = Incident::paginate(10);

        // Metrics
        $totalTickets = $this->getTotalTickets();
        $badCategorization = $this->getBadCategorization();
        $badType = $this->getBadType();
        $resolvedTickets = $this->getResolvedTickets();

        // Get ticket type metrics
        [$typeReport, $accuracyType] = $this->getTicketTypeMetrics();

        // Get category metrics
        [$categoryReport, $accuracyCategory] = $this->getCategoryMetrics();

        return view('tables.table', compact(
            'incidents',
            'serviceDesks',
            'totalTickets',
            'badCategorization',
            'badType',
            'resolvedTickets',
            'typeReport',
            'accuracyType',
            'categoryReport',
            'accuracyCategory'
        ));
    }

    /**
     * Calculate ticket type classification metrics
     */
    private function getTicketTypeMetrics() {
        $typeLabels = [0, 1]; // 0=Request, 1=Incident
        $typeReport = [];
        $totalType = Incident::count();
        $correctType = 0;

        foreach ($typeLabels as $label) {
            $tp = Incident::where('incident', $label)->count(); // Using incident column for now
            $fp = 0; // No predicted type column
            $fn = 0;
            $support = $tp;

            $precision = ($tp + $fp) > 0 ? round($tp / ($tp + $fp), 2) : 0;
            $recall = ($tp + $fn) > 0 ? round($tp / ($tp + $fn), 2) : 0;
            $f1 = ($precision + $recall) > 0 ? round(2 * ($precision * $recall) / ($precision + $recall), 2) : 0;

            $typeReport[$label] = [
                'precision' => $precision,
                'recall'    => $recall,
                'f1'        => $f1,
                'support'   => $support,
            ];

            $correctType += $tp;
        }

        $accuracyType = $totalType > 0 ? round($correctType / $totalType, 2) : 0;

        return [$typeReport, $accuracyType];
    }

    /**
     * Calculate multi-class category classification metrics
     */
    private function getCategoryMetrics() {
        $categoryLabels = Incident::select('category')->distinct()->pluck('category');
        $categoryReport = [];
        $allPrecision = $allRecall = $allF1 = $allSupport = [];
        $correctCategory = 0;

        foreach ($categoryLabels as $label) {
            $tp = Incident::where('category', $label)->where('predict_category', $label)->count();
            $fp = Incident::where('category', '!=', $label)->where('predict_category', $label)->count();
            $fn = Incident::where('category', $label)->where('predict_category', '!=', $label)->count();
            $support = Incident::where('category', $label)->count();

            $precision = ($tp + $fp) > 0 ? round($tp / ($tp + $fp), 2) : 0;
            $recall = ($tp + $fn) > 0 ? round($tp / ($tp + $fn), 2) : 0;
            $f1 = ($precision + $recall) > 0 ? round(2 * ($precision * $recall) / ($precision + $recall), 2) : 0;

            $categoryReport[$label] = [
                'precision' => $precision,
                'recall'    => $recall,
                'f1'        => $f1,
                'support'   => $support,
            ];

            $allPrecision[] = $precision;
            $allRecall[] = $recall;
            $allF1[] = $f1;
            $allSupport[] = $support;
            $correctCategory += $tp;
        }

        $totalCategory = Incident::count();
        $accuracyCategory = $totalCategory > 0 ? round($correctCategory / $totalCategory, 2) : 0;

        // Macro & Weighted Avg
        $macroPrecision = count($categoryLabels) > 0 ? round(array_sum($allPrecision) / count($allPrecision), 2) : 0;
        $macroRecall    = count($categoryLabels) > 0 ? round(array_sum($allRecall) / count($allRecall), 2) : 0;
        $macroF1        = count($categoryLabels) > 0 ? round(array_sum($allF1) / count($allF1), 2) : 0;

        $weightedPrecision = $totalCategory > 0 ? round(array_sum(array_map(fn($p,$s)=>$p*$s, $allPrecision,$allSupport)) / $totalCategory, 2) : 0;
        $weightedRecall    = $totalCategory > 0 ? round(array_sum(array_map(fn($r,$s)=>$r*$s, $allRecall,$allSupport)) / $totalCategory, 2) : 0;
        $weightedF1        = $totalCategory > 0 ? round(array_sum(array_map(fn($f,$s)=>$f*$s, $allF1,$allSupport)) / $totalCategory, 2) : 0;

        $categoryReport['macro avg'] = [
            'precision'=>$macroPrecision, 'recall'=>$macroRecall, 'f1'=>$macroF1, 'support'=>$totalCategory
        ];
        $categoryReport['weighted avg'] = [
            'precision'=>$weightedPrecision, 'recall'=>$weightedRecall, 'f1'=>$weightedF1, 'support'=>$totalCategory
        ];

        return [$categoryReport, $accuracyCategory];
    }

    private function getTotalTickets() {
        return Incident::count();
    }

    private function getBadCategorization() {
        // Example: check where ML predicted != category
        return Incident::whereColumn('predict_category', '!=', 'category')->count();
    }

    private function getBadType() {
        return Incident::where('incident', 0)->count();
    }

    private function getResolvedTickets() {
        $total = Incident::count();
        $emptyCategory = Incident::whereNull('category')->orWhere('category', '')->count();
        return $total - $emptyCategory;
    }

    public function index_single_prediction(){
        $predictions = Prediction::all();
        return view('singlePredict',compact('predictions'));
    }

    // public function deep_research(Request $request){
    //     $serviceDesks = Service_desk::all();
    //     $query = Incident::query();

    //     if ($request->filled('service_desk')) {
    //         $query->where('service_desk', $request->service_desk);
    //     }

    //     if ($request->filled('start_date') && $request->filled('end_date')) {
    //         $query->whereBetween('created_at_servicenow', [$request->start_date, $request->end_date]);
    //     }

    //     if ($request->filled('priority')) {
    //         $query->where('priority', $request->priority);
    //     }
        
    //     $incidents = $query->paginate(10);  
        
    //     // Raw counts
    //     $totalTickets = $this->getTotalTickets();
    //     $badCategorizationCount = $this->getBadCategorization();
    //     $badTypeCount = $this->getBadType();
    //     $resolvedTicketsCount = $this->getResolvedTickets();

    //     // Avoid division by zero
    //     if ($totalTickets > 0) {
    //         $badCategorization = round(($badCategorizationCount / $totalTickets) * 100, 2);
    //         $badType = round(($badTypeCount / $totalTickets) * 100, 2);
    //         $resolvedTickets = round(($resolvedTicketsCount / $totalTickets) * 100, 2);
    //     } else {
    //         $badCategorization = $badType = $resolvedTickets = 0;
    //     }

    //     return view('tables.table', compact(
    //         'incidents',
    //         'serviceDesks',
    //         'totalTickets',
    //         'badCategorization',
    //         'badType',
    //         'resolvedTickets'
    //     ));
    // }

    public function deep_research(Request $request){
        $serviceDesks = Service_desk::all();
        $query = Incident::query();

        if ($request->filled('service_desk')) {
            $query->where('service_desk', $request->service_desk);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at_servicenow', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        
        $incidents = $query->paginate(10);  

        // Raw counts
        $totalTickets = $this->getTotalTickets();
        $badCategorizationCount = $this->getBadCategorization();
        $badTypeCount = $this->getBadType();
        $resolvedTicketsCount = $this->getResolvedTickets();

        // Avoid division by zero
        if ($totalTickets > 0) {
            $badCategorization = round(($badCategorizationCount / $totalTickets) * 100, 2);
            $badType = round(($badTypeCount / $totalTickets) * 100, 2);
            $resolvedTickets = round(($resolvedTicketsCount / $totalTickets) * 100, 2);
        } else {
            $badCategorization = $badType = $resolvedTickets = 0;
        }

        // Ticket type metrics
        [$typeReport, $accuracyType] = $this->getTicketTypeMetrics();

        // Category metrics
        [$categoryReport, $accuracyCategory] = $this->getCategoryMetrics();

        return view('tables.table', compact(
            'incidents',
            'serviceDesks',
            'totalTickets',
            'badCategorization',
            'badType',
            'resolvedTickets',
            'typeReport',
            'accuracyType',
            'categoryReport',
            'accuracyCategory'
        ));
    }


    public function generate_bulk_prediction(Request $request)
    {
        $serviceDesks = Service_desk::all();
        $ids = explode(',', $request->incident_ids);
        $incidents = Incident::whereIn('id', $ids)->paginate(10);

        foreach ($incidents as $incident) {
            $text = $incident->short_description . ' ' . $incident->description;

            // Predict if it's an incident or not
            $commandIncidentOrNot = "python3 " 
                . escapeshellarg("/home/sedra/Work/ITU_M2/Stage/predictiveAI/ITSM_predict/Python/laravelPredictCatML.py") 
                . " " . escapeshellarg($text);

            // Predict category
            $commandCat = "python3 " 
                . escapeshellarg("/home/sedra/Work/ITU_M2/Stage/predictiveAI/ITSM_predict/Python/catPredictLar.py") 
                . " " . escapeshellarg($text);

            $predictionIncidentOrNot = trim(shell_exec($commandIncidentOrNot));
            $predictionCat = trim(shell_exec($commandCat));

            // Metrics
            $totalTickets = $this->getTotalTickets();
            $badCategorization = $this->getBadCategorization();
            $badType = $this->getBadType();
            $resolvedTickets = $this->getResolvedTickets();

            return view('tables.table', compact(
                'incidents',
                'serviceDesks',
                'totalTickets',
                'badCategorization',
                'badType',
                'resolvedTickets'
            ));
        }
    }


    public function predict_category(Request $request)
    {
        // ncident, error, outage, panne, problème, failure
        $text = $request->input('ticket_text');

        // Escape special characters for shell
        $escapedText = escapeshellarg($text);

        // Call Python script
        $command = "python3 /home/sedra/Work/ITU_M2/Stage/predictiveAI/ITSM_predict/Python/laravelPredictCatML.py " . escapeshellarg($text);
        $commandCat = "python3 /home/sedra/Work/ITU_M2/Stage/predictiveAI/ITSM_predict/Python/catPredictLar.py ".escapeshellarg($text);
        $output = shell_exec($command);
        $outputCat = shell_exec($commandCat);

        $prediction = Prediction::updateOrCreate(
            [
                'short_description'=> '', 
                'description'=> $text,
                'predict_category'=> $outputCat,
                'confidence_score'=> 0,
                'incident'=> $output
            ]
        );
        $predictions = Prediction::orderBy('id', 'desc')->get();
        return view('singlePredict',compact('predictions'));
    }


    public function import_incidents(Request $request)
    {
        // 1️⃣ Validate form inputs
        $request->validate([
            'service_desk' => 'required|exists:service_desks,id',
            'priority'     => 'required|in:1,2,3,4',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
        ]);

        // 2️⃣ Get Service Desk details (sys_id)
        $serviceDesk = Service_desk::findOrFail($request->service_desk);

        // 3️⃣ Call ServiceNow API
        $username = "AIData";
        $password = "267A#j+]dBCPtbO<ZVf)8GQs92UBJ2kYxYE={stzN6OS9MDrWtM6BypYcVDm;XrHgCm;vn2RYNnky-OT-)[G,O;R>-ie[#Wkqz>>";
        $response = Http::withBasicAuth($username,$password)
            ->get('https://axiandev.service-now.com/api/now/table/incident', [
                'sysparm_fields' => 'number,u_affected_user,assignment_group,category,priority,u_opco,short_description,description,sys_created_on',
                'sysparm_query'  => sprintf(
                    'u_opco=%s^priority=%s^sys_created_onBETWEENjavascript:gs.dateGenerate("%s","00:00:00")@javascript:gs.dateGenerate("%s","23:59:59")',
                    $serviceDesk->sys_id,
                    $request->priority,
                    $request->start_date,
                    $request->end_date
                )
            ]);

        // 4️⃣ Check API response
        if (!$response->successful()) {
            return back()->with('error', 'Failed to connect to ServiceNow API.');
        }

        $incidents = $response->json()['result'] ?? [];

        $groupNames = [];
        $companyNames = [];
        $userNames = [];


        // 5️⃣ Store incidents in database
        foreach ($incidents as $inc) {
            $groupId = $inc['assignment_group']['value'] ?? $inc['assignment_group'] ?? '';
            $companyId = $inc['u_opco']['value'] ?? $inc['u_opco'] ?? '';
            $userId = $inc['u_affected_user']['value'] ?? $inc['u_affected_user'] ?? '';

            if (!isset($groupNames[$groupId])) {
                $groupNames[$groupId] = self::getGroupNameById($groupId);
            }

            if (!isset($companyNames[$companyId])) {
                $companyNames[$companyId] = self::getCompanyNameById($companyId);
            }

            if (!isset($userNames[$userId])) {
                $userNames[$userId] = self::getNameById($userId);
            }

            $inc['group_name'] = $groupNames[$groupId];
            $inc['company_name'] = $companyNames[$companyId];
            $inc['user_name'] = $userNames[$userId];

            Incident::updateOrCreate(
                ['number' => $inc['number']], // prevent duplicates
                [
                    'requested_for'   => $inc['user_name'] ?? $inc['u_affected_user'] ?? '',
                    'category'        => $inc['category'] ?? '',
                    'priority'        => $inc['priority'] ?? '',
                    'service_desk'    => $serviceDesk->name,
                    'assignment_group'=> $inc['group_name'] ?? '',
                    'short_description'=> $inc['short_description'] ?? '', 
                    'description'     => $inc['description'] ?? '',
                    'created_at_servicenow' => $inc['sys_created_on'] ?? '',
                    'predict_category'=> '',
                    'incident'        => 1, 
                ]
            );
        }

        return back()->with('success', count($incidents) . ' incidents imported successfully!');
    }

    
    
    public static function getIncidents()
    {
        $response = Http::withBasicAuth(self::$username, self::$password)
            ->get('https://axiandev.service-now.com/api/now/table/incident', [
                'sysparm_limit' => 5,
                'sysparm_fields' => 'number,u_affected_user,assignment_group,category,u_opco,short_description,description'
            ]);

        if ($response->successful()) {
            $incidents = $response->json()['result']; 
        } else {
            $incidents = [];
        }
        return $incidents;
    }

    public static function getGroupNameById($groupId)
    {
        $username = "AIData";
        $password = "267A#j+]dBCPtbO<ZVf)8GQs92UBJ2kYxYE={stzN6OS9MDrWtM6BypYcVDm;XrHgCm;vn2RYNnky-OT-)[G,O;R>-ie[#Wkqz>>";
        $response = Http::withBasicAuth($username,$password)
            ->get("https://axiandev.service-now.com/api/now/table/sys_user_group/{$groupId}", [
                'sysparm_fields' => 'name'
            ]);

        if ($response->successful()) {
            return $response->json()['result']['name'] ?? null;
        }
        return null;
    }

    public static function getCompanyNameById($companyId)
    {
        $username = "AIData";
        $password = "267A#j+]dBCPtbO<ZVf)8GQs92UBJ2kYxYE={stzN6OS9MDrWtM6BypYcVDm;XrHgCm;vn2RYNnky-OT-)[G,O;R>-ie[#Wkqz>>";
        $response = Http::withBasicAuth($username, $password)
            ->get("https://axiandev.service-now.com/api/now/table/core_company/{$companyId}", [
                'sysparm_fields' => 'name'
            ]);

        if ($response->successful()) {
            return $response->json()['result']['name'] ?? null;
        }

        return null;
    }

    public static function getNameById($userId)
    {
        $username = "AIData";
        $password = "267A#j+]dBCPtbO<ZVf)8GQs92UBJ2kYxYE={stzN6OS9MDrWtM6BypYcVDm;XrHgCm;vn2RYNnky-OT-)[G,O;R>-ie[#Wkqz>>";
        $response = Http::withBasicAuth($username, $password)
            ->get("https://axiandev.service-now.com/api/now/table/sys_user/{$userId}", [
                'sysparm_fields' => 'name'
            ]);

        if ($response->successful()) {
            return $response->json()['result']['name'] ?? null;
        }

        return null;
    }

}
