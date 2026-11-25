<?php

namespace App\Controller;

use PDOException;
use App\Model\UserModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\UserService;

final class UserController extends AbstractController
{
    public function __construct(private UserService $userservice){}

    #[Route('/user', name: 'app_user')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }

    // This function as the goal to verify the credentail the vue give
    #[Route('/user/login', name:'api-login-attempt', methods:['POST'])]
    public function loginAttempt(Request $request): JsonResponse
    {
        $UserToLog = new UserModel(
                0, // No real need to give an id in that case
                $request->toArray()['username'],
                "" ,// No real need to get the email in that case
                $request->toArray()['password'],
                "", // No real need aswell to give name or nickname in that form of the formular
                "",
            );
        try{
            $result = $this->userservice->checkUser($UserToLog->get_username(), $UserToLog->get_password());

            // In the case that the credential the controler are the same than the database 
            if ($result){
                return new JsonResponse([
                    "message" => "Connection reussis",
                ], 201);
            } else{
                return new JsonResponse([
                    "error" => "Les informations fournites sont invalides, veuillez re-essayer à nouveau"
                ], 403);
            }
        }catch(PDOException $e){
            return new JsonResponse([
                "error" => "Erreur lors de la connexion à la base de donnée",
            ], 503);
        } 
    }    

    #[Route('/user/createLogin', name: 'api-createLogin', methods: ['POST'])]
    public function createLogin(Request $request) : JsonResponse{
        try{
            // Hash the password
            $hashedPassword = password_hash($request->toArray()['password'], PASSWORD_DEFAULT);

            // Verify if the credential doesnt exist
            $UserToCreate = new UserModel(
                0, // No real need to give an id in that case
                $request->toArray()['username'],
                $request->toArray()['email'],
                $hashedPassword, // Putting the hashed password into the object
                "", // No real need aswell to give name or nickname in that form of the formular
                "",
            );
            

            $result = $this->userservice->checkIfAccountIsAlreadyLinked($UserToCreate->get_email(), $UserToCreate->get_username());
            if ($result) { // In the case the credential are valid, they already exist, nedd to abord the process
                return new JsonResponse([
                    "error" => "L'adresse email ou le username fournit dont déjà inscrit dans la base de donné"
                ], 403 );
            }
            // If the credential doesnt exist, make it 
            $result = $this->userservice->createUser($UserToCreate->get_email(), $UserToCreate->get_username(), $UserToCreate->get_password());
        
            if (!$result) {
                return new JsonResponse([
                    "error" => "Le compte na pas éte crée pour une raisons, veuillez re-essayer"
                ], 400);
            }
            return new JsonResponse([], 201);
        
        } catch(PDOException $e){
            return new JsonResponse([
                "error" => "Erreur lors de la connection à la base de donnée"
            ], 503);
        }
        // Return the confirmation of the creation of the credentials


    }

}
