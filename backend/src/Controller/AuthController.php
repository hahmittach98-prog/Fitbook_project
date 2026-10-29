<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api')]
class AuthController extends AbstractController
{
    #[Route('/register', name: 'api_register', methods: ['POST'])]
    public function register(
        Request $request,
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $em,
        UserRepository $users,
        ValidatorInterface $validator,
        MailerInterface $mailer
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        if (!is_array($data)) {
            return $this->json(['erreur' => 'JSON invalide'], Response::HTTP_BAD_REQUEST);
        }

        $constraints = new Assert\Collection([
            'nom' => [new Assert\NotBlank(), new Assert\Length(max: 50)],
            'prenom' => [new Assert\NotBlank(), new Assert\Length(max: 50)],
            'email' => [new Assert\NotBlank(), new Assert\Email()],
            'password' => [new Assert\NotBlank(), new Assert\Length(min: 8, minMessage: '8 caractères minimum')],
            'role' => [new Assert\NotBlank(), new Assert\Choice(choices: ['sportif', 'coach', 'salle'])],
            'telephone' => new Assert\Optional([new Assert\Length(max: 50)]),
        ]);

        $violations = $validator->validate($data, $constraints);
        if (count($violations) > 0) {
            $erreurs = [];
            foreach ($violations as $v) {
                $erreurs[$v->getPropertyPath()] = $v->getMessage();
            }
            return $this->json(['erreurs' => $erreurs], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($users->findOneBy(['email' => $data['email']])) {
            return $this->json(['erreur' => 'Email déjà utilisé'], Response::HTTP_CONFLICT);
        }

        $roles = match ($data['role']) {
            'coach' => ['ROLE_COACH'],
            'salle' => ['ROLE_SALLE'],
            default => ['ROLE_SPORTIF'],
        };

        $user = new User();
        $user->setEmail($data['email']);
        $user->setNom($data['nom']);
        $user->setPrenom($data['prenom']);
        $user->setTelephone($data['telephone'] ?? null);
        $user->setRoles($roles);
        $user->setPassword($hasher->hashPassword($user, $data['password']));
        $user->setIsVerified(false);
        $user->setDateInscription(new \DateTimeImmutable());
        $user->setTokenConfirmation(bin2hex(random_bytes(32)));

        $em->persist($user);
        $em->flush();
                $lien = $request->getSchemeAndHttpHost() . '/api/confirm-email/' . $user->getTokenConfirmation();
        $email = (new Email())
            ->from('no-reply@fitbook.local')
            ->to($user->getEmail())
            ->subject('FitBook : confirmez votre email')
            ->text("Bienvenue sur FitBook !\nConfirmez votre compte : " . $lien);
        try {
            $mailer->send($email);
        } catch (\Throwable $e) {
            // l'inscription reste valide même si l'envoi échoue
        }

         return $this->json(
            ['message' => 'Compte créé. Vérifiez votre email pour le confirmer.', 'id' => $user->getId()],
            Response::HTTP_CREATED
        );
    }

#[Route('/confirm-email/{token}', name: 'api_confirm_email', methods: ['GET'])]
    public function confirmEmail(string $token, UserRepository $users, EntityManagerInterface $em): JsonResponse
    {
        $user = $users->findOneBy(['tokenConfirmation' => $token]);
        if (!$user) {
            return $this->json(['erreur' => 'Token invalide'], Response::HTTP_NOT_FOUND);
        }
        $user->setIsVerified(true);
        $user->setTokenConfirmation(null);
        $em->flush();

        return $this->json(['message' => 'Email confirmé, vous pouvez vous connecter.']);
    }
}