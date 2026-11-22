<?php

namespace App\Controller;

use PDOException;
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

    #[Route('/user/login', name:'api-login-attempt', methods:['POST'])]
    public function loginAttempt(Request $request): JsonResponse
    {
        try{
            $result = $this->userservice->checkUser($request->toArray()['username'], $request->toArray()['password']);

            // In the case that the credential the controler are the same than the database 
            if ($result){
                return new JsonResponse([
                    "message" => "Connection succeful, welcome aboard",
                ], 200);
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
}
