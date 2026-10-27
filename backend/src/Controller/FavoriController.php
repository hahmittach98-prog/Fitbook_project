<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\CoachRepository;
use App\Repository\CoursRepository;
use App\Repository\SalleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/favoris')]
class FavoriController extends AbstractController
{
    #[Route('', name: 'api_favori_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        /** @var User $u */
        $u = $this->getUser();

        return $this->json([
            'salles' => array_map(
                fn ($s) => ['id' => $s->getId(), 'nom' => $s->getNom(), 'ville' => $s->getVille()],
                $u->getFavorisSalles()->toArray()
            ),
            'coachs' => array_map(
                fn ($c) => ['id' => $c->getId(), 'specialite' => $c->getSpecialite()],
                $u->getFavorisCoachs()->toArray()
            ),
            'cours' => array_map(
                fn ($c) => ['id' => $c->getId(), 'nom' => $c->getNom()],
                $u->getFavorisCours()->toArray()
            ),
        ]);
    }

    #[Route('/salles/{id}', name: 'api_favori_salle_add', methods: ['POST'])]
    public function addSalle(int $id, SalleRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        $salle = $repo->find($id);
        if (!$salle) {
            return $this->json(['erreur' => 'Salle introuvable'], Response::HTTP_NOT_FOUND);
        }
        /** @var User $u */
        $u = $this->getUser();
        $u->addFavorisSalle($salle);
        $em->flush();

        return $this->json(['message' => 'Salle ajoutée aux favoris'], Response::HTTP_CREATED);
    }

    #[Route('/salles/{id}', name: 'api_favori_salle_remove', methods: ['DELETE'])]
    public function removeSalle(int $id, SalleRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        $salle = $repo->find($id);
        if ($salle) {
            /** @var User $u */
            $u = $this->getUser();
            $u->removeFavorisSalle($salle);
            $em->flush();
        }

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/coachs/{id}', name: 'api_favori_coach_add', methods: ['POST'])]
    public function addCoach(int $id, CoachRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        $coach = $repo->find($id);
        if (!$coach) {
            return $this->json(['erreur' => 'Coach introuvable'], Response::HTTP_NOT_FOUND);
        }
        /** @var User $u */
        $u = $this->getUser();
        $u->addFavorisCoach($coach);
        $em->flush();

        return $this->json(['message' => 'Coach ajouté aux favoris'], Response::HTTP_CREATED);
    }

    #[Route('/coachs/{id}', name: 'api_favori_coach_remove', methods: ['DELETE'])]
    public function removeCoach(int $id, CoachRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        $coach = $repo->find($id);
        if ($coach) {
            /** @var User $u */
            $u = $this->getUser();
            $u->removeFavorisCoach($coach);
            $em->flush();
        }

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/cours/{id}', name: 'api_favori_cours_add', methods: ['POST'])]
    public function addCours(int $id, CoursRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        $cours = $repo->find($id);
        if (!$cours) {
            return $this->json(['erreur' => 'Cours introuvable'], Response::HTTP_NOT_FOUND);
        }
        /** @var User $u */
        $u = $this->getUser();
        $u->addFavorisCour($cours);
        $em->flush();

        return $this->json(['message' => 'Cours ajouté aux favoris'], Response::HTTP_CREATED);
    }

    #[Route('/cours/{id}', name: 'api_favori_cours_remove', methods: ['DELETE'])]
    public function removeCours(int $id, CoursRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        $cours = $repo->find($id);
        if ($cours) {
            /** @var User $u */
            $u = $this->getUser();
            $u->removeFavorisCour($cours);
            $em->flush();
        }

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}