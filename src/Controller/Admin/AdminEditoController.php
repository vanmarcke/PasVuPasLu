<?php

namespace App\Controller\Admin;

use App\Controller\HelperTrait;
use App\Entity\Edito;
use App\Form\EditoType;
use App\Repository\EditoRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminEditoController extends AbstractController
{
    use HelperTrait;

    /**
     * @Route("/admin/edito", name="admin-edito")
     * @param EditoRepository $editoRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editos(EditoRepository $editoRepository)
    {
        return $this->render('admin/pages/edito.html.twig', [
            'editos' => $editoRepository->findBy([], ['created_at' => 'DESC']),
        ]);
    }


    /**
     * @Route("/admin/newEdito", name="admin_newedito", methods={"GET", "POST"})
     * @param Request $request
     * @param ObjectManager $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function NewEdito(Request $request, ObjectManager $em)
    {
        $edito = new Edito();

        $form = $this->createForm(EditoType::class, $edito);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($edito);
            $em->flush();
            $this->addFlash('success', 'L\'édito '.$edito->getTitle().' a bien été ajouté');
            return $this->redirectToRoute('admin-edito');
        }

        return $this->render('admin/pages/addedito.html.twig', [
            'form' => $form->createView()
        ]);

    }


    /**
     * @Route("admin/edit-edito/{id}", name="admin_edito_edit")
     * @param ObjectManager $em
     * @param Request $request
     * @param Edito $edito
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit_edito(ObjectManager $em, Request $request, Edito $edito) {

        $editEdito = $this->createForm("App\Form\EditoType", $edito);
        $editEdito->handleRequest($request);

        if($editEdito->isSubmitted() && $editEdito->isValid()) {

            //Sauvegarde en BDD
            $em=$this->getDoctrine()->getManager();
            $em->persist($edito);
            $em->flush();
            // Notification
            $this->addFlash('success','L\'édito a bien été mis à jour !');
            return $this->redirectToRoute('admin-edito');
        }


        return $this->render("admin/pages/editEdito.html.twig", [
            'form' => $editEdito->createView(),
            'edito' => $edito
        ]);

    }


    /**
     * @Route("admin/delete/edito/{id}", name="admin_edito_delete", methods={"DELETE"})
     * @param Request $request
     * @param ObjectManager $em
     * @param Edito $edito
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete_edito(Request $request, ObjectManager $em, Edito $edito) {

        if($this->isCsrfTokenValid('delete' . $edito->getId(), $request->get("_token"))) {
            $em->remove($edito);
            $em->flush();
            $this->addFlash('success', 'l\'édito '.$edito->getTitle().' a été supprimé avec succès');
        }

        return $this->redirectToRoute("admin-edito");

    }


}
