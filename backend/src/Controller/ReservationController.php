<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use App\Repository\SeanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/reservations')]
class ReservationController extends AbstractController
{
    private function toArray(Reservation $r): array
    {
        return [
            'id' => $r->getId(),
            'sportif' => trim(($r->getUser()?->getPrenom() ?? '') . ' ' . ($r->getUser()?->getNom() ?? '')),
            'date' => $r->getDateReservation()?->format('Y-m-d H:i'),
            'statut' => $r->getStatut(),
            'dateAnnulation' => $r->getDateAnnulation()?->format('Y-m-d H:i'),
            'seanceId' => $r->getSeance()?->getId(),
            'cours' => $r->getSeance()?->getCours()?->getNom(),
            'dateSeance' => $r->getSeance()?->getDateSeance()?->format('Y-m-d'),
            'creeLe' => $r->getCreatedAt()?->format('Y-m-d H:i'),
        ];
    }

    #[Route('', name: 'api_reservation_mine', methods: ['GET'])]
    public function mine(ReservationRepository $repo): JsonResponse
    {
        $liste = $repo->findBy(['user' => $this->getUser()], ['dateReservation' => 'DESC']);

        return $this->json(array_map(fn ($r) => $this->toArray($r), $liste));
    }

    #[Route('', name: 'api_reservation_create', methods: ['POST'])]
    public function create(
        Request $request,
        SeanceRepository $seances,
        ReservationRepository $repo,
        EntityManagerInterface $em,
        MailerInterface $mailer
    ): JsonResponse {
        $this->denyAccessUnlessGranted('ROLE_SPORTIF');
        $d = json_decode($request->getContent(), true) ?? [];

        $seance = isset($d['seanceId']) ? $seances->find($d['seanceId']) : null;
        if (!$seance) {
            return $this->json(['erreur' => 'Séance introuvable'], Response::HTTP_NOT_FOUND);
        }
        if ($seance->getStatut() !== 'ouverte') {
            return $this->json(['erreur' => "Cette séance n'est pas ouverte"], Response::HTTP_CONFLICT);
        }
        if ($repo->findOneBy(['user' => $this->getUser(), 'seance' => $seance, 'statut' => 'confirmee'])) {
            return $this->json(['erreur' => 'Vous avez déjà réservé cette séance'], Response::HTTP_CONFLICT);
        }
        // Contrôle de capacité
        if ($repo->countConfirmed($seance) >= $seance->getCapaciteMax()) {
            return $this->json(['erreur' => 'Séance complète'], Response::HTTP_CONFLICT);
        }

        $r = new Reservation();
        $r->setUser($this->getUser());
        $r->setSeance($seance);
        $r->setStatut('confirmee');
        $r->setDateReservation(new \DateTimeImmutable());
        $em->persist($r);
        $em->flush();

        try {
            $mailer->send((new Email())
                ->from('no-reply@fitbook.local')
                ->to($this->getUser()->getEmail())
                ->subject('FitBook : réservation confirmée')
                ->text('Votre réservation pour la séance du ' . $seance->getDateSeance()->format('d/m/Y') . ' est confirmée.'));
        } catch (\Throwable $e) {
            // la réservation reste valide même si l'envoi échoue
        }

        return $this->json($this->toArray($r), Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_reservation_cancel', methods: ['DELETE'])]
    public function cancel(Reservation $reservation, EntityManagerInterface $em): JsonResponse
    {
        if ($reservation->getUser() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['erreur' => 'Accès refusé'], Response::HTTP_FORBIDDEN);
        }
        $reservation->setStatut('annulee');
        $reservation->setDateAnnulation(new \DateTimeImmutable());
        $em->flush();

        return $this->json($this->toArray($reservation));
    }

    #[Route('/seance/{id}', name: 'api_reservation_par_seance', methods: ['GET'])]
    public function parSeance(int $id, SeanceRepository $seances, ReservationRepository $repo): JsonResponse
    {
        if (!$this->isGranted('ROLE_COACH') && !$this->isGranted('ROLE_SALLE') && !$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['erreur' => 'Accès refusé'], Response::HTTP_FORBIDDEN);
        }
        $seance = $seances->find($id);
        if (!$seance) {
            return $this->json(['erreur' => 'Séance introuvable'], Response::HTTP_NOT_FOUND);
        }

        return $this->json(array_map(fn ($r) => $this->toArray($r), $repo->findBy(['seance' => $seance])));
    }
}