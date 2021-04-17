<?php

namespace App\Controller\Admin;


use App\Entity\CouponLivre;
use App\Form\CouponLivreFormType;
use App\Repository\CouponLivreRepository;
use App\Repository\AllCouponsRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminCouponLivreController extends AbstractController
{

    /**
     * @Route("/admin/coupon/livre", name="admin_couponLivre")
     * @param Request $request
     * @param CouponLivreRepository $couponRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function coupon(Request $request, CouponLivreRepository $couponRepository) {

       $coupon = new CouponLivre();

        $form = $this->createForm(CouponLivreFormType::class, $coupon)
            ->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()){

            $em=$this->getDoctrine()->getManager();
            $em->persist($coupon);
            $em->flush();

            // Notification
            $this->addFlash('success',
                'Le code promo a été ajouté avec succès');

            return $this->redirectToRoute('admin_couponLivre');
        }

        $listCoupons = $couponRepository->findBy([], ['created_at' => 'DESC']);

        return $this->render('admin/pages/couponLivre.html.twig', [
            'form'=>$form->createView(),
            'coupons' => $listCoupons
        ]);


    }
    
    
    /**
     * @Route("/admin/coupon/verifi/user/{id}", name="admin_v_coupon", methods="GET")
     * @param AllCouponsRepository $allcouponsRepository
     * @param UserRepository $userRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function verefi($id, AllCouponsRepository $allcouponsRepository, UserRepository $userRepository) {

        $allcoupon = $allcouponsRepository->findOneBy(['promo_id' => $id, 'coupon_id' => null]);
        
        if($allcoupon) {
            
            $user = $userRepository->findOneBy(['id' => $allcoupon->getUserId()]);
         
            return new Response(json_encode(['status' => 'success', 'id' => $user->getId(), 'nom' => $user->getNom(), 'prenom' => $user->getPrenom(), 'date' => $allcoupon->getAdddateAt()]));
            
        }

         return new Response(json_encode(['status' => 'errors', 'request' => "Aucun utilisateur n'a encore utiliser ce code promo !"]));

    }
    
    
    /**
     * @Route("/admin/coupon/verifi/user/{id}/full", name="admin_v_coupon_full", methods="GET")
     * @param AllCouponsRepository $allcouponsRepository
     * @param UserRepository $userRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function verefi2($id, AllCouponsRepository $allcouponsRepository, UserRepository $userRepository, ObjectManager $em) {
        
        // SELECT u.nom, u.prenom, u.id FROM App\Entity\AllCoupons a LEFT OUTER JOIN App\Entity\User u ON a.user_id = u.id where a.coupon_id = :id

        $query = $em->createQuery('SELECT u.nom, u.prenom, u.id, a.adddate_at FROM App\Entity\AllCoupons a LEFT OUTER JOIN App\Entity\User u WITH a.user_id = u.id WHERE a.coupon_id = :id');
        $query->setParameters(['id' => $id]);
        $users = $query->getResult(); // array
        
        if($users) {
            
            $u = [];
            foreach($users as $value) {
                $u[] = $value;
            }
         
            return new Response(json_encode(['status' => 'success', 'request' => $u]));
            
        }

         return new Response(json_encode(['status' => 'errors', 'request' => "Aucun utilisateur n'a encore utiliser ce code promo !"]));

    }
    


    /**
     * @Route("/admin/delete/coupon/livre/{id}", name="admin_delete_couponLivre", methods="DELETE")
     * @param Coupon $coupon
     * @param Request $request
     * @param ObjectManager $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(CouponLivre $coupon, Request $request, ObjectManager $em) {

        if($this->isCsrfTokenValid('delete' . $coupon->getId(), $request->get("_token"))) {
            $em->remove($coupon);
            $em->flush();
            $this->addFlash("success", "Le coupon à bien ete supprimer");
        }

        return $this->redirectToRoute("admin_couponLivre");

    }

}
