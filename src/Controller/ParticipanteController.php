<?php

namespace App\Controller;

use App\Entity\Mensaje;
use App\Form\MensajeType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;

class ParticipanteController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/participante', name: 'app_participante')]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(MensajeType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            //creo y subo la mesa si no tiene errores
            $mensaje = ($form->getData()); 
            $entityManager = $doctrine->getManager();

            $mensaje->setEmisor($this->getUser());
            $mensaje->setValido(false);
            //hacer flash de exito o erroe
            try {
                $entityManager->persist($mensaje);
                $entityManager->flush();
                
                $this->addFlash(
                    'success',
                    'Mensaje creado!'
                );
            } catch (\Throwable $th) {
                $this->addFlash(
                    'error',
                    '¡No se ha podido actualizar mesa!'
                );
                    //throw $th;
            }
                //}else{
                    //}
        }
        //dd($this->getUser()->getMensajes());
        return $this->render('participante/index.html.twig', [
            //'mensajes' => $this->getUser()->getMensajes()
            'form' => $form,
            'mensajes' => $this->getUser()->getMensajes(),
        ]);
    }

    #[IsGranted('ROLE_JUEZ')]
    #[Route('/validar', name: 'app_validar')]
    public function valida(Request $request, ManagerRegistry $doctrine): Response
    {
        return $this->render('participante/validar.html.twig', [
        ]);
    }
}
