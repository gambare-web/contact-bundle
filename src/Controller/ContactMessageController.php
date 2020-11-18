<?php

namespace Gambare\ContactBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Gambare\ContactBundle\Entity\ContactMessage;
use Gambare\ContactBundle\Form\ContactMessageFormType;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

/**
 *
 * IMPORTANT NOTE
 * the methods in this controller should not be used.
 * They are here for demo purpose only
 * Instead copy what you need in your own controller.
 */
class ContactMessageController extends AbstractController
{
    /**
     * Register a message in the db from a form.
     * No email
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function simpleMessage(Request $request, EntityManagerInterface $em)
    {
        $contact_form = $this->createForm(ContactMessageFormType::class);
        $contact_form->handleRequest($request);
        if ($contact_form->isSubmitted() && $contact_form->isValid()) {

            /** @var ContactMessage $contact */
            $contact = $contact_form->getData();
            $contact->setTo(null);
            $em->persist($contact);
            $em->flush();
            $this->addFlash('success', 'Message envoyé.');
        }

        return $this->render(
            'demo_contact.html.twig',
            [
                'contact_form' => $contact_form->createView(),
            ]
        );
    }

    /**
     * Register a message in the db from a form and send an email
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @param MailerInterface $mailer
     *
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function emailMessage(Request $request, EntityManagerInterface $em, MailerInterface $mailer)
    {
        $contact_form = $this->createForm(ContactMessageFormType::class);
        $contact_form->handleRequest($request);
        if ($contact_form->isSubmitted() && $contact_form->isValid()) {

            /** @var ContactMessage $contact */
            $contact = $contact_form->getData();
            $contact->setTo($_ENV['ADMIN_EMAIL']);

            $em->persist($contact);
            $em->flush();

                 $email = (new TemplatedEmail())
                     ->from($_ENV['FROM_EMAIL'])
                     ->to($contact->getTo())
                     ->subject($contact->getSubject())
                     ->htmlTemplate('emails/contact_notif.html.twig')
                     ->context(
                         [
                             'name'    => $contact->getName(),
                             'subject' => $contact->getSubject(),
                             'mail'    => $contact->getEmail(),
                             'phone'     => $contact->getPhone(),
                             'message' => $contact->getMessage(),
                             'logo_url' => null,
                             'logo_name' => 'W00tKorp',
                         ]
                     );

            if($contact->getFilename()){
                $email->attachFromPath('/uploads/contact_message/'.$contact->getFilename(), 'Message attachment');
            }

            try {
                $mailer->send($email);
            } catch (TransportExceptionInterface $e) {
                $this->addFlash('success', 'Message enregistré');
            }
            $this->addFlash('success', 'Message envoyé.');
        }

        return $this->render(
            'demo_contact.html.twig',
            [
                'contact_form' => $contact_form->createView(),
            ]
        );
    }
}