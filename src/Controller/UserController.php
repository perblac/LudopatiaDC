<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/user')]
class UserController extends AbstractController
{
    
    
    #[Route('/', name: 'app_user_index', methods: ['GET', 'POST'])]
    public function index(UserRepository $userRepository, Request $request): Response
    {
        
        
        
        
        

        $users = $userRepository->findAll();
        $nuevaLista = [];
        foreach($users as $user) {
            $coupons = $user->getCoupons()->toArray();
            $userAsArray['id'] = $user->getId();
            $userAsArray['username'] = $user->getUsername();
            $userAsArray['roles'] = $user->getRoles();
            $userAsArray['cash'] = $user->getCash();
            $totalSpent = 0;
            $totalPrizes = 0;
            foreach($coupons as $coupon) {
                $totalSpent += $coupon->getSorteo()->getCouponPrice();
                if ($coupon->getState() > 1) ++$totalPrizes;
            }
            $userAsArray['totalSpent'] = $totalSpent;
            $userAsArray['totalPrizes'] = $totalPrizes;
            $nuevaLista[] = $userAsArray;
        }

        

        if ($request->request->get('byCash')) {
            usort($nuevaLista, function ($a, $b) {
                if ($a['cash'] == $b['cash']) {
                    return 0;
                }
                return ($a['cash'] > $b['cash']) ? -1 : 1;
            });
        }
        if($request->request->get('byTotalSpent')){
            usort($nuevaLista, function ($a, $b) {
                if ($a['totalSpent'] == $b['totalSpent']) {
                    return 0;
                }
                return ($a['totalSpent'] > $b['totalSpent']) ? -1 : 1;
            });
        }
        if($request->request->get('byTotalPrizes')){
            usort($nuevaLista, function ($a, $b) {
                if ($a['totalPrizes'] == $b['totalPrizes']) {
                    return 0;
                }
                return ($a['totalPrizes'] > $b['totalPrizes']) ? -1 : 1;
            });
        }

        return $this->render('user/index.html.twig', [
            'users' => $nuevaLista,
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
