<?php

namespace App\Controller;

use App\Entity\Disponibilite;
use App\Repository\CoachRepository;
use App\Repository\DisponibiliteRepository;
use App\Repository\SalleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/disponibilites')]
class DispoController extends AbstractController
{
    private function toArray(Disponibilite $d): array
    {
        return [
            'id' => $d->getId(),
            'jourSemaine' => $d->getJourSemaine(),
            'heureDebut' => $d->getHeureDebut()?->format('H:i'),
            'heureFin' => $d->getHeureFin()?->format('H:i'),
            'coachId' => $d->getCoach()?->getId(),
            'salleId' => $d->getSalle()?->getId(),
        ];
    }

    #[Route('', name: 'api_dispo_index', methods: ['GET'])]
    public function index(Request $request, DisponibiliteRepository $repo): JsonResponse
    {
        $criteres = [];
        if ($request->query->get('coachId')) {
            $criteres['coach'] = (int) $request->query->get('coachId');
        }
        if ($request->query->get('salleId')) {
            $criteres['salle'] = (int) $request->query->get('salleId');
        }

        return $this->json(array_map(fn ($d) => $this->toArray($d), $repo->findBy($criteres)));
    }

    #[Route('', name: 'api_dispo_create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        CoachRepository $coachs,
        SalleRepository $salles
    ): JsonResponse {
        if (!$this->isGranted('ROLE_COACH') && !$this->isGranted('ROLE_SALLE')) {
            return $this->json(['erreur' => 'Accès refusé'], Response::HTTP_FORBIDDEN);
        }
        $d = json_decode($request->getContent(), true) ?? [];

        foreach (['jourSemaine', 'heureDebut', 'heureFin'] as $champ) {
            if (empty($d[$champ])) {
                return $this->json(['erreur' => "Champ requis : $champ"], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }
        if (empty($d['coachId']) === empty($d['salleId'])) {
            return $this->json(['erreur' => 'Indiquer un coachId OU un salleId'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        if ($d['heureFin'] <= $d['heureDebut']) {
            return $this->json(['erreur' => "L'heure de fin doit être après l'heure de début"], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $dispo = new Disponibilite();
        $dispo->setJourSemaine($d['jourSemaine']);
        $dispo->setHeureDebut(new \DateTimeImmutable($d['heureDebut']));
        $dispo->setHeureFin(new \DateTimeImmutable($d['heureFin']));

        if (!empty($d['coachId'])) {
            $coach = $coachs->find($d['coachId']);
            if (!$coach) {
                return $this->json(['erreur' => 'Coach introuvable'], Response::HTTP_NOT_FOUND);
            }
            $dispo->setCoach($coach);
        } else {
            $salle = $salles->find($d['salleId']);
            if (!$salle) {
                return $this->json(['erreur' => 'Salle introuvable'], Response::HTTP_NOT_FOUND);
            }
            $dispo->setSalle($salle);
        }

        $em->persist($dispo);
        $em->flush();

        return $this->json($this->toArray($dispo), Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_dispo_delete', methods: ['DELETE'])]
    public function delete(Disponibilite $dispo, EntityManagerInterface $em): JsonResponse
    {
        if (!$this->isGranted('ROLE_COACH') && !$this->isGranted('ROLE_SALLE') && !$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['erreur' => 'Accès refusé'], Response::HTTP_FORBIDDEN);
        }
        $em->remove($dispo);
        $em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}