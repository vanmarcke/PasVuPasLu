<?php

namespace App\Controller\Admin;


use App\Entity\Coupon;
use App\Form\CouponFormType;
use App\Repository\CouponRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminCouponController extends AbstractController
{

    /**
     * @Route("/admin/coupon", name="admin_coupon")
     * @param Request $request
     * @param CouponRepository $couponRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function coupon(Request $request, CouponRepository $couponRepository) {

       $coupon = new Coupon();

        $form = $this->createForm(CouponFormType::class, $coupon)
            ->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()){

            $em=$this->getDoctrine()->getManager();
            $em->persist($coupon);
            $em->flush();

            // Notification
            $this->addFlash('success',
                'Le code promo a été ajouté avec succès');

            return $this->redirectToRoute('admin_coupon');
        }

        $listCoupons = $couponRepository->findBy([], ['creatdate_at' => 'DESC']);

        return $this->render('admin/pages/coupon.html.twig', [
            'form'=>$form->createView(),
            'coupons' => $listCoupons
        ]);


    }


    /**
     * @Route("/admin/delete/coupon/{id}", name="admin_delete_coupon", methods="DELETE")
     * @param Coupon $coupon
     * @param Request $request
     * @param ObjectManager $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Coupon $coupon, Request $request, ObjectManager $em) {

        if($this->isCsrfTokenValid('delete' . $coupon->getId(), $request->get("_token"))) {
            $em->remove($coupon);
            $em->flush();
            $this->addFlash("success", "La coupon ".$coupon->getPromo()." à bien ete supprimer");
        }

        return $this->redirectToRoute("admin_coupon");

    }

}
