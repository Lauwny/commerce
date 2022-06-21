<?php
    
    
    namespace App\Controllers;

use App\Models\ScenarioModel;
use CodeIgniter\RESTful\ResourceController;
use Exception;
use \Firebase\JWT\JWT;
use PHPUnit\Util\Json;

class Scenario extends ResourceController
{
    /** Get a scenario by his id. @TODO : Add check for shared or private scenario **/
    public function getScenarioById($idScenario)
    {

        $kv = new KeyValidator();
        $authHeader = $this->request->getHeader("Authorization");
        $authHeader = $authHeader->getValue();
        $token = $authHeader;

        try {
            $decoded = $kv->decodeToken($token);
            if ($decoded) {
                $scenarioModel = new ScenarioModel();
                $scenario = $scenarioModel->find($idScenario);
                if(!empty($scenario)) {
                    $response = [
                        'status' => 200,
                        'error' => false,
                        'messages' => 'Scenarios found',
                        'data' => [
                            'scenario' => $scenario
                        ]
                    ];
                    return $this->respondCreated($response);
                } else {
                    $response = [
                        'status' => 500,
                        'error' => true,
                        'messages' => 'No scenario found',
                        'data' => []
                    ];
                    return $this->respondCreated($response);
                }
            }
        } catch (Exception $ex) {

            $response = [
                'status' => 401,
                'error' => true,
                'messages' => 'Access denied. Bad credential.',
                'data' => []
            ];
            return $this->respondCreated($response);
        }
    }

    /** Get a scenario by userId. @TODO : Add check for shared or privates scenarios ( ex 10 scenarios for user id 2 with  8 shared, 2 privates will return 8 scenarios ) **/
    public function getScenariosByUser($idUser){
        $kv = new KeyValidator();
        $authHeader = $this->request->getHeader("Authorization");
        $authHeader = $authHeader->getValue();
        $token = $authHeader;

        try {
            $decoded = $kv->decodeToken($token);
            if ($decoded) {
                $scenarioModel = new ScenarioModel();
                $scenario = $scenarioModel->where('idCreatorScenario',$idUser)->findAll();
                if(!empty($scenario)) {
                    $response = [
                        'status' => 200,
                        'error' => false,
                        'messages' => 'Scenarios found',
                        'data' => [
                            'scenario' => $scenario
                        ]
                    ];
                    return $this->respondCreated($response);
                } else {
                    $response = [
                        'status' => 500,
                        'error' => true,
                        'messages' => 'No scenario found',
                        'data' => []
                    ];
                    return $this->respondCreated($response);
                }
            }
        } catch (Exception $ex) {

            $response = [
                'status' => 401,
                'error' => true,
                'messages' => 'Access denied. Bad credential.',
                'data' => []
            ];
            return $this->respondCreated($response);
        }
    }

    /**
     * Returns all scenarios for the claimer
     * Yeah this shit look like "getScenariosByUser" but for this case we should show private scenarios cause only the owner of the scenarios can see them ...
     **/
    public function getSelfScenario(){
        $kv = new KeyValidator();
        $authHeader = $this->request->getHeader("Authorization");
        $authHeader = $authHeader->getValue();
        $token = $authHeader;

        try {
            $decoded = $kv->decodeToken($token);
            if ($decoded) {
                $scenarioModel = new ScenarioModel();
                $scenario = $scenarioModel->where('idCreatorScenario', $decoded->idUser)->findAll();
                if(!empty($scenario)) {
                    $response = [
                        'status' => 200,
                        'error' => false,
                        'messages' => 'Scenarios found',
                        'data' => [
                            'scenario' => $scenario
                        ]
                    ];
                    return $this->respondCreated($response);
                } else {
                    $response = [
                        'status' => 500,
                        'error' => true,
                        'messages' => 'No scenario found',
                        'data' => []
                    ];
                    return $this->respondCreated($response);
                }
            }
        } catch (Exception $ex) {

            $response = [
                'status' => 401,
                'error' => true,
                'messages' => 'Access denied. Bad credential.',
                'data' => []
            ];
            return $this->respondCreated($response);
        }
    }

}
?>