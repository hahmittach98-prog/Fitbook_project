<?php

namespace App\Controller;

use App\Entity\Salle;
use App\Repository\SalleRepository;
use App\Repository\CoachRepository;
use App\Repository\CoursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/salles')]
class SalleController extends AbstractController
{
    private function toArray(Salle $s): array
    {
        return [
            'id' => $s->getId(),
            'nom' => $s->getNom(),
            'ville' => $s->getVille(),
            'adresse' => $s->getAdresse(),
            'capacite' => $s->getCapacite(),
            'telephone' => $s->getTelephone(),
            'description' => $s->getDescription(),
            'photo' => $s->getPhoto(),
            'gestionnaireId' => $s->getGestionnaire()?->getId(),
        ];
    }

    #[Route('', name: 'api_salle_index', methods: ['GET'])]
    public function index(Request $request, SalleRepository $repo): JsonResponse
    {
        $capaciteMin = $request->query->get('capaciteMin');
        $salles = $repo->search(
            $request->query->get('q'),
            $request->query->get('ville'),
            $capaciteMin !== null ? (int) $capaciteMin : null
        );

        return $this->json(array_map(fn ($s) => $this->toArray($s), $salles));
    }

    #[Route('/{id}', name: 'api_salle_show', methods: ['GET'])]
    public function show(Salle $salle): JsonResponse
    {
        return $this->json($this->toArray($salle));
    }

    #[Route('', name: 'api_salle_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_SALLE');
        $d = json_decode($request->getContent(), true) ?? [];

        foreach (['nom', 'ville', 'adresse', 'capacite'] as $champ) {
            if (empty($d[$champ])) {
                return $this->json(['erreur' => "Champ requis : $champ"], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }
        if ((int) $d['capacite'] <= 0) {
            return $this->json(['erreur' => 'La capacité doit être positive'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $s = new Salle();
        $s->setNom($d['nom']);
        $s->setVille($d['ville']);
        $s->setAdresse($d['adresse']);
        $s->setCapacite((int) $d['capacite']);
        $s->setTelephone($d['telephone'] ?? null);
        $s->setDescription($d['description'] ?? null);
        $s->setPhoto($d['photo'] ?? null);
        $s->setGestionnaire($this->getUser());
        $em->persist($s);
        $em->flush();

        return $this->json($this->toArray($s), Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_salle_update', methods: ['PUT'])]
    public function update(Salle $salle, Request $request, EntityManagerInterface $em): JsonResponse
    {
        if ($salle->getGestionnaire() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['erreur' => 'Accès refusé'], Response::HTTP_FORBIDDEN);
        }
        $d = json_decode($request->getContent(), true) ?? [];

        if (isset($d['nom'])) { $salle->setNom($d['nom']); }
        if (isset($d['ville'])) { $salle->setVille($d['ville']); }
        if (isset($d['adresse'])) { $salle->setAdresse($d['adresse']); }
        if (isset($d['capacite']) && (int) $d['capacite'] > 0) { $salle->setCapacite((int) $d['capacite']); }
        if (array_key_exists('telephone', $d)) { $salle->setTelephone($d['telephone']); }
        if (array_key_exists('description', $d)) { $salle->setDescription($d['description']); }
        if (array_key_exists('photo', $d)) { $salle->setPhoto($d['photo']); }
        $em->flush();

        return $this->json($this->toArray($salle));
    }

    #[Route('/{id}', name: 'api_salle_delete', methods: ['DELETE'])]
    public function delete(Salle $salle, EntityManagerInterface $em): JsonResponse
    {
        if ($salle->getGestionnaire() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['erreur' => 'Accès refusé'], Response::HTTP_FORBIDDEN);
        }
        $em->remove($salle);
        $em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

        #[Route('/{id}/cours', name: 'api_salle_cours_list', methods: ['GET'])]
    public function listCours(Salle $salle): JsonResponse
    {
        return $this->json(array_map(
            fn ($c) => ['id' => $c->getId(), 'nom' => $c->getNom(), 'niveau' => $c->getNiveau()],
            $salle->getCours()->toArray()
        ));
    }

    #[Route('/{id}/cours/{coursId}', name: 'api_salle_cours_add', methods: ['POST'])]
    public function addCours(Salle $salle, int $coursId, CoursRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        if ($salle->getGestionnaire() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['erreur' => 'Accès refusé'], Response::HTTP_FORBIDDEN);
        }
        $cours = $repo->find($coursId);
        if (!$cours) {
            return $this->json(['erreur' => 'Cours introuvable'], Response::HTTP_NOT_FOUND);
        }
        $salle->addCours($cours);
        $em->flush();

        return $this->json(['message' => 'Cours ajouté à la salle'], Response::HTTP_CREATED);
    }

    #[Route('/{id}/cours/{coursId}', name: 'api_salle_cours_remove', methods: ['DELETE'])]
    public function removeCours(Salle $salle, int $coursId, CoursRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        if ($salle->getGestionnaire() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['erreur' => 'Accès refusé'], Response::HTTP_FORBIDDEN);
        }
        $cours = $repo->find($coursId);
        if ($cours) {
            $salle->removeCours($cours);
            $em->flush();
        }

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/{id}/coachs/{coachId}', name: 'api_salle_coach_add', methods: ['POST'])]
    public function addCoach(Salle $salle, int $coachId, CoachRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        if ($salle->getGestionnaire() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['erreur' => 'Accès refusé'], Response::HTTP_FORBIDDEN);
        }
        $coach = $repo->find($coachId);
        if (!$coach) {
            return $this->json(['erreur' => 'Coach introuvable'], Response::HTTP_NOT_FOUND);
        }
        $salle->addCoach($coach);
        $em->flush();

        return $this->json(['message' => 'Coach ajouté à la salle'], Response::HTTP_CREATED);
    }

    #[Route('/{id}/coachs/{coachId}', name: 'api_salle_coach_remove', methods: ['DELETE'])]
    public function removeCoach(Salle $salle, int $coachId, CoachRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        if ($salle->getGestionnaire() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['erreur' => 'Accès refusé'], Response::HTTP_FORBIDDEN);
        }
        $coach = $repo->find($coachId);
        if ($coach) {
            $salle->removeCoach($coach);
            $em->flush();
        }

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}