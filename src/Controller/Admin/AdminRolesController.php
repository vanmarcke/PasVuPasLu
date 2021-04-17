<?php

namespace App\Controller\Admin;


use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminRolesController extends AbstractController
{

    /**
     * @Route("admin/roles", name="admin_roles")
     * @param UserRepository $adminRepository
     * @param Request $request
     * @param ObjectManager $em
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function adminRoles(UserRepository $adminRepository, Request $request, ObjectManager $em, UserPasswordEncoderInterface $encoder) {

        $user = new User();

        $form = $this->createForm(UserFormType::class, $user)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $pasword = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($pasword);

            $user->setRoles(['ROLE_ADMIN']);

            $em=$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success','L\'utilisateur a bien été créé avec le rôle administrateur');

            return $this->redirectToRoute('admin_roles');

        }

        return $this->render("admin/pages/adminRoles.html.twig", [
            'admins' => $adminRepository->findLatestAuteurs('ROLE_ADMIN'),
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("admin/remove/{id}", name="admin_remove_roles", methods={"POST"})
     * @param ObjectManager $em
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeRole(ObjectManager $em, User $user, Request $request) {

        if($this->isCsrfTokenValid('delete' . $user->getId(), $request->get("_token"))) {
            $em->persist($user->setRoles(['ROLE_AUTEUR']));
            $em->flush();
            $this->addFlash("success", "Le rôle administrateur a été retiré pour l'utilisateur ".$user->getNom().' '.$user->getPrenom());
        }

        return $this->redirectToRoute("admin_roles");

    }

}
