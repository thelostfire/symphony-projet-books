<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    public function __construct(private MailerInterface $mailer) 
    {  
    }
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();
            $user->setPassword($plainPassword);
            $user->setJoinDate(new DateTime('now'));

            // encode the plain password
            // $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Nous confirmons votre inscription, veuillez vous connecter pour profiter de nos services !');

            // do anything else you need here, like send an email
            $email = (new Email())
            ->from('hello@example.com')
            ->to($user->getEmail())
            ->subject('Inscription à un site de livres ! ( ͡° ͜ʖ ͡°)')
            ->text('Nous confirmons que vous vous êtes bien inscrit à notre site de livres')
            ->html('<p>Nous confirmons que vous vous êtes bien inscrit à notre site de livres</p>');

            $this->mailer->send($email);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
    /*#[Route('/verify', name:'app_verify')]
    public function verifyUserEmail(): Response
    {
        //TODO
    }*/
}
