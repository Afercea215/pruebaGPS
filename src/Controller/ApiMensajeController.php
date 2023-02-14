<?php

namespace App\Controller;

use App\Entity\Mensaje;
use App\Repository\BandaRepository;
use App\Repository\MensajeRepository;
use App\Repository\ModoRepository;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class ApiMensajeController extends AbstractController
{
    #[Route('/api/mensaje', name: 'app_api_mensaje_getAll', methods:'GET')]
    public function getAll(MensajeRepository $mj): JsonResponse
    {   
        $mensajes = [];
        foreach ($mj->findAll() as $key => $mensaje) {
            $mensajes[] = $mensaje->toArray();
        }
        return $this->json(['status'=>'exito', 'mensajes' => $mensajes], 200);
    }

    #[Route('/api/mensaje/{id}', name: 'app_api_mensaje_get', methods:'GET')]
    public function get(Mensaje $ms=null): JsonResponse
    {   
        if ($ms) {
            return $this->json(['status'=>'exito', 'mensaje' => $ms->toArray()], 200);
        }else{
            return $this->json(['status'=>'error', 'error' => 'No se ha encontrado el mensaje con la id especificada'], 404);
        }
    }

    #[Route('/api/mensaje', name: 'app_api_mensaje_new', methods:'POST')]
    public function post(ManagerRegistry $doctrine, Request $request, ModoRepository $mr, BandaRepository $br, UserRepository $ur): JsonResponse
    {   
        $entityManager = $doctrine->getManager();
        
        try {
            $m = new Mensaje();
            
            $m->setModo($mr->findOneById($request->request->get('modo')));
            $m->setBanda($br->findOneById($request->request->get('banda')));
            $m->setEmisor($ur->findOneById($request->request->get('emisor')));
            $m->setReceptor($ur->findOneById($request->request->get('receptor')));
            $m->setFecha(new DateTime($request->request->get('fecha')));
            $m->setValido($request->request->get('valido') == 'false' ? false : true);

            $entityManager->persist($m);
            $entityManager->flush();
      
            return $this->json('Mensaje creado con la id ' . $m->getId(),201);
        } catch (\Throwable $th) {
            return $this->json('Error, no se ha podido crear el mensaje',401);
        }
    }

    #[Route('/api/mensaje/{id}', name: 'app_api_mensaje_put', methods:'PUT')]
    public function put(ManagerRegistry $doctrine, Request $request, MensajeRepository $mer, ModoRepository $mr, BandaRepository $br, UserRepository $ur, int $id): JsonResponse
    {   
        $entityManager = $doctrine->getManager();
        
        try {
            $m = $mer->findOneById($id);

            if (!$m) {
                return $this->json('No se ha encontrado ningun mensaje con la id -> ' . $id, 404);
            }
            
            $m->setModo($mr->findOneById($request->request->get('modo')));
            $m->setBanda($br->findOneById($request->request->get('banda')));
            $m->setEmisor($ur->findOneById($request->request->get('emisor')));
            $m->setReceptor($ur->findOneById($request->request->get('receptor')));
            $m->setFecha(new DateTime($request->request->get('fecha')));
            $m->setValido($request->request->get('valido') == 'false' ? false : true);

            $entityManager->persist($m);
            $entityManager->flush();
      
            return $this->json(['status'=>'exito','mensaje' => $m->toArray()],200);
        } catch (\Throwable $th) {
            return $this->json('Error, no se ha podido modificar el mensaje',401);
        }
    }


    #[Route('/api/mensaje/{id}', name: 'app_api_mensaje_put', methods:'DELETE')]
    public function delete(ManagerRegistry $doctrine, MensajeRepository $mer, int $id): JsonResponse
    {   
        $entityManager = $doctrine->getManager();
        
        try {
            $m = $mer->findOneById($id);

            if (!$m) {
                return $this->json('No se ha encontrado ningun mensaje con la id -> ' . $id, 404);
            }
            
            $entityManager->remove($m);
            $entityManager->flush();
      
            return $this->json(['status'=>'exito'],200);
        } catch (\Throwable $th) {
            return $this->json('Error, no se ha podido eliminar el mensaje',401);
        }
    }
    
}
