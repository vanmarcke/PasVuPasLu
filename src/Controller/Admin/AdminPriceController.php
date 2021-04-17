<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Prices;
use App\Form\PriceFormType;
use App\Repository\PricesRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Services\random;
use Doctrine\Common\Persistence\ObjectManager;
use App\Controller\HelperTrait;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AdminPriceController extends AbstractController
{
    use HelperTrait;

    /**
     * @Route("/admin/prices", name="admin_prices")
     * @param PricesRepository $pricesRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listPrices(PricesRepository $PricesRepository) {

        return $this->render("admin/pages/Listprices.html.twig", [
            'prices' => $PricesRepository->findBy([], ['creatAt' => 'DESC']),
            'countprice' => $PricesRepository->countPrices(),
        ]);

    }


    /**
     * @Route("admin/new/prices", name="new_prices")
     * @param Request $request
     * @param random $random
     * @param ObjectManager $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newPrice(Request $request, random $random, ObjectManager $em)
    {   
        $price = new prices;
        $form = $this->createForm(PriceFormType::class, $price)
        ->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()){

                /** @var UploadedFile $image*/
                $photo = $form['photo']->getData();
                if($photo){
                    $fileName = $this->slugify($random->random(10)).'.'.$photo->guessExtension();
                        // Move the file to the directory where brochures are stored
                        try {
                            $photo->move($this->getParameter('prix_assets_dir'), $fileName);
                        } catch (FileException $e) {
                            die("Une erreur s'est produite lors du téléchargement de l'image sur le serveur veuillez réessayer ultérieurement.");
                        }
                        $price->setPhoto($fileName);
                } else {
                        $price->setPhoto('d.png');
                    }

            //Sauvegarde en BDD
            $em=$this->getDoctrine()->getManager();
            $em->persist($price);
            $em->flush();

            $this->addFlash('success','Le prix a bien été ajouté');

            return $this->redirectToRoute('admin_prices');
        }

        return $this->render('admin/pages/newprices.html.twig', [
            'controller_name' => 'AdminPriceController',
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/edit/price/{id}", name="admin_edit_price")
     * @param Prices $price
     * @param Request $request
     * @param random $random
     * @param ObjectManager $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editPrice(Prices $price, Request $request, random $random, ObjectManager $em) {

        $form = $this->createForm(PriceFormType::class, $price)
            ->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()){

              /** @var UploadedFile $image*/
              $photo = $form['photo']->getData();
              if($photo){
                  $fileName = $this->slugify($random->random(10)).'.'.$photo->guessExtension();
                      // Move the file to the directory where brochures are stored
                      try {
                          $photo->move($this->getParameter('prix_assets_dir'), $fileName);
                      } catch (FileException $e) {
                          die("Une erreur s'est produite lors du téléchargement de l'image sur le serveur veuillez réessayer ultérieurement.");
                      }
                      $price->setPhoto($fileName);
              } else {
                $price->setPhoto($request->get('temporary'));
                  }


            // $price->setPhoto($request->get('temporary'));

            //Sauvegarde en BDD
            $em=$this->getDoctrine()->getManager();
            $em->persist($price);
            $em->flush();

            $this->addFlash('success','Les modifications effectuées sur le prix ont été enregistrées !');

            return $this->redirectToRoute('admin_prices');
        }

        return $this->render("admin/pages/editprice.html.twig", [
            'form' => $form->createView(),
            'price' => $price
        ]);

    }

     /**
     * @Route("/admin/delete/price/{id}", name="admin_delete_price", methods="DELETE")
     * @param Prices $price
     * @param Request $request
     * @param ObjectManager $em
     * @param CommentRepository $commentRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete_price(Prices $price, Request $request, ObjectManager $em) {

        

        if($this->isCsrfTokenValid('delete' . $price->getId(), $request->get("_token"))) {
            
            $em->remove($price);
            $em->flush();
            $this->addFlash("success", "Le prix a bien été supprimé");
        }

        return $this->redirectToRoute("admin_prices");

    }

}
