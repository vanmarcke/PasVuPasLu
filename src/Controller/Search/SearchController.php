<?php

namespace App\Controller\Search;



use App\Repository\LivresRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{

    /**
     * Creates a new ActionItem entity.
     *
     * @Route("/search", name="search", methods={"GET"})
     * @param Request $request
     * @param LivresRepository $livresRepository
     * @return Response
     */
    public function searchAction(Request $request, LivresRepository $livresRepository)
    {
        $s = $request->get('q');
        $entities = $livresRepository->search($s, 10);

        if (!$entities) {
            $result['entities']['error'] = "Aucun résultat correspondant à vos termes de recherche n'a été trouvé.";
        } else {
            $result['entities'] = $this->getRealEntities($entities);
        }
        return new Response(json_encode($result));
    }


    public function getRealEntities($entities)
    {
        foreach ($entities as $entity) {
            $realEntities[$entity->getId()] = [
                'title' => $entity->getTitre(),
                'slug' => $entity->getSlug().'/'.$entity->getId(),
                'photo' => $entity->getPhoto(),
                'category' => $entity->getCategorie()->getCategorie(),
                'userNom' => $entity->getUser()->getNom(),
                'userId' => $entity->getUser()->getId(),
                'userPrenom' => $entity->getUser()->getPrenom()
            ];
        }
        return $realEntities;
    }

}
