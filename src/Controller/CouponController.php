<?php

namespace App\Controller;

use App\Entity\Coupon;
use App\Form\CouponType;
use App\Repository\CouponRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/coupon')]
class CouponController extends AbstractController
{
    #[Route('/', name: 'app_coupon_index', methods: ['GET'])]
    public function index(CouponRepository $couponRepository): Response
    {
        return $this->render('coupon/index.html.twig', [
            'coupons' => $couponRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_coupon_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $coupon = new Coupon();
        $form = $this->createForm(CouponType::class, $coupon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($coupon);
            $entityManager->flush();

            return $this->redirectToRoute('app_coupon_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('coupon/new.html.twig', [
            'coupon' => $coupon,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_coupon_show', methods: ['GET'])]
    public function show(Coupon $coupon): Response
    {
        return $this->render('coupon/show.html.twig', [
            'coupon' => $coupon,
        ]);
    }

    
    #[Route('/buy/{id}', name: 'app_coupon_buy', methods: ['GET','POST'])]
    public function buyCoupon(Coupon $coupon, Request $request, EntityManagerInterface $entityManager): Response
    {
        $state = 0;
        $sorteo = $coupon->getSorteo();
        $precioCupon = $coupon->getSorteo()->getCouponPrice();
        $user = $this->getUser();
        $saldoUser = $user->getCash();

        if ($saldoUser < $precioCupon) {
            // no hay pasta, state a 1 para notificarlo en la vista
            $state = 1;
        }

        if ($coupon->getState() != 0) {
            $state = 2;
        }

        if ($request->request->get('buy') && $state == 0) {
            // se compra el cupon:
            // restamos pelas
            $saldoUser -= $precioCupon;
            // damos el cupon al user
            $coupon->setOwner($user);
            $coupon->setState(1);
            $entityManager->persist($coupon);

            $user->setCash($saldoUser);
            $user->addCoupon($coupon);
            $entityManager->persist($user);
            // grabamos
            $entityManager->flush();

            //volvemos a lista cupones
            return $this->redirectToRoute('app_sorteo_show', [
                'id' => $sorteo->getId(),
            ], Response::HTTP_SEE_OTHER);
        }
        return $this->render('coupon/buyCoupon.html.twig', [
            'coupon' => $coupon,
            'state' => $state,
            'sorteo' => $sorteo,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_coupon_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Coupon $coupon, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CouponType::class, $coupon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_coupon_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('coupon/edit.html.twig', [
            'coupon' => $coupon,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_coupon_delete', methods: ['POST'])]
    public function delete(Request $request, Coupon $coupon, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$coupon->getId(), $request->request->get('_token'))) {
            $entityManager->remove($coupon);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_coupon_index', [], Response::HTTP_SEE_OTHER);
    }
}
