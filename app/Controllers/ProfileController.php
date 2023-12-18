<?php


namespace App\Controllers;


use App\Models\UtilisateurModel;
use Helpers\JWTHandler\JwtHandler;
use Helpers\Sanitizer\SanitizeArray;
use App\Models\UserProfileModel;

class ProfileController extends \CodeIgniter\RESTful\ResourceController
{
    private $userProfileModel;
    private $userModel;
    private $userData;
    private $userProfileData;
    private $adresseData;

    public function __construct(){
    }

    public function updateUser(){
        $this->logger("info", "ProfileController::updateProfile init");
        $this->userProfileModel = new UserProfileModel();
        $jwtHandler = new JwtHandler();
        $requestData = SanitizeArray::sanitizeObject($this->request->getJSON());
        $token = $requestData->token;
        $infoToUpdate = $requestData->data->user;
        $userId = $jwtHandler->getUserId($token);
        $this->userModel = new UtilisateurModel();
        $isUpdated = $this->userModel->update($userId, $infoToUpdate);
        if($isUpdated){
            echo $this->userModel->getLastQuery()->getQuery();
        }else{
            echo "not updated";
        }
    }

    public function updateProfile(){}

    public function updateAdresse(){}

    public function getAllProfiles(){

    }

    public function getOneProfile($idProfile){

    }

    public function getSelfProfile(){

    }
}
