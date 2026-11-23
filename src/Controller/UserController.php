<?php

namespace App\Controller;

use PDOException;
use PhpParser\Node\Expr\FuncCall;
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
        try{
            $result = $this->userservice->checkUser($request->toArray()['username'], $request->toArray()['password']);

            // In the case that the credential the controler are the same than the database 
            if ($result){
                return new JsonResponse([
                    "message" => "Connection succeful, welcome aboard",
                ], 201);
            } else{
                return new JsonResponse([
                    "error" => "Wrong credential, try again"
                ], 403);
            }
        }catch(PDOException $e){
            return new JsonResponse([
                "message" => "Database Connection error",
                "error" => $e
            ], 503);
        } 
    }    

    #[Route('/user/createLogin', name: 'api-createLogin', methods: ['POST'])]
    public function createLogin(Request $request) : JsonResponse{
        try{
            // Verify if the credential doesnt exist
            $result = $this->userservice->checkIfAccountIsAlreadyLinked($request->toArray()['email'], $request->toArray()['username']);
            if ($result) { // In the case the credential are valid, they already exist, nedd to abord the process
                return new JsonResponse([
                    "error" => "L'adresse email ou le username fournit dont déjà inscrit dans la base de donné"
                ], 403 );
            }
            // If the credential doesnt exist, make it 
            $result = $this->userservice->createUser($request->toArray()['email'], $request->toArray()['username'], $request->toArray()['password']);
        
            if (!$result) {
                return new JsonResponse([
                    "error" => "Le compte na pas éte crée pour une raisons, veuillez re-essayer"
                ], 400);
            }
            return new JsonResponse([], 201);
        
        } catch(PDOException $e){
        }
        // Return the confirmation of the creation of the credentials


    }
}
