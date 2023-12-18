<?php


namespace App\Controllers;


use App\Models\AdresseModel;
use App\Models\UserAdresseModel;
use App\Models\UtilisateurModel;
use Helpers\JWTHandler\JwtHandler;
use Helpers\RestResponse\RestResponse;
use Helpers\Sanitizer\SanitizeArray;
use App\Models\UserProfileModel;

class ProfileController extends \CodeIgniter\RESTful\ResourceController
{
    private $userProfileModel;
    private $userModel;
    private $adresseModel;
    private $userAdresseModel;
    private $jwtHandler;
    private $httpCode;
    private $restResponse;
    public function __construct(){
        $this->userModel = new UtilisateurModel();
        $this->userProfileModel = new UserProfileModel();
        $this->jwtHandler = new JwtHandler();
        $this->adresseModel = new AdresseModel();
        $this->userAdresseModel = new UserAdresseModel();
    }

    public function updateUser(){
        $this->logger("info", "ProfileController::updateUser init");
        $requestData = SanitizeArray::sanitizeObject($this->request->getJSON());
        $token = $requestData->token;
        $infoToUpdate = $requestData->data->user;
        $userId = $this->jwtHandler->getUserId($token);
        $isUpdated = $this->userModel->update($userId, $infoToUpdate);
        if($isUpdated){
            $this->restResponse = new RestResponse([
                "code" => "update_user_succesfull",
                "message" => "Credentials updated",
                "data" => ""
            ]);
            $this->httpCode = 200;
        }else{
            $this->restResponse = new RestResponse([
                "code" => "update_user_failure",
                "message" => "Credentials update failure",
                "data" => ""
            ]);
            $this->httpCode = 404;
        }
        return $this->respond(json_decode($this->restResponse->build()), $this->httpCode);
    }

    public function updateProfile(){
        $this->logger("info", "ProfileController::updateProfile init");
        $requestData = SanitizeArray::sanitizeObject($this->request->getJSON());
        $token = $requestData->token;
        $infoToUpdate = $requestData->data->userprofile;
        $userId = $this->jwtHandler->getUserId($token);
        $isUpdated = $this->userProfileModel->update($userId, $infoToUpdate);
        echo $this->userProfileModel->getLastQuery()->getQuery();
        if($isUpdated){
            $this->restResponse = new RestResponse([
                "code" => "update_profile_succesfull",
                "message" => "Profile updated",
                "data" => ""
            ]);
            $this->httpCode = 200;
        }else{
            $this->restResponse = new RestResponse([
                "code" => "update_profile_failure",
                "message" => "Profile update failure",
                "data" => ""
            ]);
            $this->httpCode = 404;
        }
        return $this->respond(json_decode($this->restResponse->build()), $this->httpCode);
    }

    public function updateAdresse(){
        $this->logger("info", "ProfileController::updateAdresse init");
        $requestData = SanitizeArray::sanitizeObject($this->request->getJSON());
        $token = $requestData->token;
        $infoToUpdate = $requestData->data->userAdresse;
        $userId = $this->jwtHandler->getUserId($token);
        $adresseId = $this->userAdresseModel->where("idUser", $userId)->first();
        if($adresseId != 0){
            $isUpdated = $this->userProfileModel->update($userId, $infoToUpdate);
            echo $this->userProfileModel->getLastQuery()->getQuery();
            if($isUpdated){
                $this->restResponse = new RestResponse([
                    "code" => "update_profile_succesfull",
                    "message" => "Profile updated",
                    "data" => ""
                ]);
                $this->httpCode = 200;
            }else{
                $this->restResponse = new RestResponse([
                    "code" => "update_profile_failure",
                    "message" => "Profile update failure",
                    "data" => ""
                ]);
                $this->httpCode = 404;
            }
        }

        return $this->respond(json_decode($this->restResponse->build()), $this->httpCode);
    }

    public function getAllProfiles(){

    }

    public function getOneProfile($idProfile){

    }

    public function getSelfProfile(){

    }
}
