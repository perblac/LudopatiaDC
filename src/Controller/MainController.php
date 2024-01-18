<?php

namespace App\Controller;

use App\Repository\SorteoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(SorteoRepository $sorteoRepository): Response
    {
        if ($this->getUser()) {
            $sorteos = [];
            if (!$this->isGranted('ROLE_ADMIN')) {
                $sorteos = $sorteoRepository->findAll();
            }
            return $this->render('main/index.html.twig', [
                'controller_name' => 'MainController',
                'sorteos' => $sorteos,
            ]);
        }
        return $this->redirectToRoute('app_login');
    }
    #[Route('/addcash', name: 'app_add_cash')]
    public function addCash(Request $request, EntityManagerInterface $entityManager){
        if ($this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            if($request->request->get("cash")){
                $cash = $request->request->get("cash");
                $actualCash = $this->getUser()->getCash();
                $actualCash += $cash;
                $this->getUser()->setCash($actualCash);

                $entityManager->flush();

                return $this->redirectToRoute('app_main');
                
            }

            return $this->render('main/add_cash.html.twig', [
                'controller' => 'MainController',
            ]);
        }
        return $this->redirectToRoute('app_main');
    }
}
