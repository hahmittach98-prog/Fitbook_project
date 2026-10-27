<?php

namespace App\Controller;

use App\Entity\Seance;
use App\Repository\CoachRepository;
use App\Repository\CoursRepository;
use App\Repository\SalleRepository;
use App\Validator\JsonSchemaValidator;
use App\Repository\SeanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/seances')]
class SeanceController extends AbstractController
{
    private function toArray(Seance $s): array
    {
        return [
            'id' => $s->getId(),
            'date' => $s->getDateSeance()?->format('Y-m-d'),
            'heureDebut' => $s->getHeureDebut()?->format('H:i'),
            'heureFin' => $s->getHeureFin()?->format('H:i'),
            'capaciteMax' => $s->getCapaciteMax(),
            'prix' => $s->getPrix(),
            'statut' => $s->getStatut(),
            'coachId' => $s->getCoach()?->getId(),
            'coursId' => $s->getCours()?->getId(),
            'coursNom' => $s->getCours()?->getNom(),
            'salleId' => $s->getSalle()?->getId(),
            'salleNom' => $s->getSalle()?->getNom(),
        ];
    }

    #[Route('', name: 'api_seance_index', methods: ['GET'])]
    public function index(Request $request, SeanceRepository $repo): JsonResponse
    {
        $prixMax = $request->query->get('prixMax');
        $liste = $repo->search(
            $request->query->get('date'),
            $request->query->get('salleId') ? (int) $request->query->get('salleId') : null,
            $request->query->get('coachId') ? (int) $request->query->get('coachId') : null,
            $request->query->get('coursId') ? (int) $request->query->get('coursId') : null,
            $prixMax !== null ? (float) $prixMax : null
        );

        return $this->json(array_map(fn ($s) => $this->toArray($s), $liste));
    }

    #[Route('/{id}', name: 'api_seance_show', methods: ['GET'])]
    public function show(Seance $seance): JsonResponse
    {
        return $this->json($this->toArray($seance));
    }

    #[Route('', name: 'api_seance_create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        CoachRepository $coachs,
        CoursRepository $cours,
        SalleRepository $salles,
        JsonSchemaValidator $schema
    ): JsonResponse {
        if (!$this->isGranted('ROLE_COACH') && !$this->isGranted('ROLE_SALLE')) {
            return $this->json(['erreur' => 'Accès refusé'], Response::HTTP_FORBIDDEN);
        }
        $d = json_decode($request->getContent(), true) ?? [];
        
        

        $coach = $coachs->find($d['coachId']);
        $cours = $cours->find($d['coursId']);
        $salle = $salles->find($d['salleId']);
        if (!$coach || !$cours || !$salle) {
            return $this->json(['erreur' => 'Coach, cours ou salle introuvable'], Response::HTTP_NOT_FOUND);
        }
        if ($d['heureFin'] <= $d['heureDebut']) {
            return $this->json(['erreur' => "L'heure de fin doit être après l'heure de début"], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        if ((int) $d['capaciteMax'] <= 0 || (int) $d['capaciteMax'] > $salle->getCapacite()) {
            return $this->json(['erreur' => 'Capacité invalide (positive et inférieure ou égale à celle de la salle)'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $s = new Seance();
        $s->setDateSeance(new \DateTimeImmutable($d['date']));
        $s->setHeureDebut(new \DateTimeImmutable($d['heureDebut']));
        $s->setHeureFin(new \DateTimeImmutable($d['heureFin']));
        $s->setCapaciteMax((int) $d['capaciteMax']);
        $s->setPrix((float) $d['prix']);
        $s->setStatut('ouverte');
        $s->setCoach($coach);
        $s->setCours($cours);
        $s->setSalle($salle);
        $em->persist($s);
        $em->flush();

        return $this->json($this->toArray($s), Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_seance_update', methods: ['PUT'])]
    public function update(Seance $seance, Request $request, EntityManagerInterface $em): JsonResponse
    {
        if (!$this->isGranted('ROLE_COACH') && !$this->isGranted('ROLE_SALLE') && !$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['erreur' => 'Accès refusé'], Response::HTTP_FORBIDDEN);
        }
        $d = json_decode($request->getContent(), true) ?? [];

        if (isset($d['date'])) { $seance->setDateSeance(new \DateTimeImmutable($d['date'])); }
        if (isset($d['heureDebut'])) { $seance->setHeureDebut(new \DateTimeImmutable($d['heureDebut'])); }
        if (isset($d['heureFin'])) { $seance->setHeureFin(new \DateTimeImmutable($d['heureFin'])); }
        if (isset($d['capaciteMax']) && (int) $d['capaciteMax'] > 0) { $seance->setCapaciteMax((int) $d['capaciteMax']); }
        if (isset($d['prix'])) { $seance->setPrix((float) $d['prix']); }
        if (isset($d['statut'])) { $seance->setStatut($d['statut']); }
        $em->flush();

        return $this->json($this->toArray($seance));
    }

    #[Route('/{id}', name: 'api_seance_delete', methods: ['DELETE'])]
    public function delete(Seance $seance, EntityManagerInterface $em): JsonResponse
    {
        if (!$this->isGranted('ROLE_COACH') && !$this->isGranted('ROLE_SALLE') && !$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['erreur' => 'Accès refusé'], Response::HTTP_FORBIDDEN);
        }
        $em->remove($seance);
        $em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}