<?php

namespace App\Http\Controllers;

use App\Models\{Incident,Ml_prediction_historie,Service_desk,Prediction};
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;


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
   
    public function index_template() {
        $serviceDesks = Service_desk::all();
        $incidents = Incident::paginate(10);

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
