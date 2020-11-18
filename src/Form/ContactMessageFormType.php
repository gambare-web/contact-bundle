<?php

namespace Gambare\ContactBundle\Form;


use Gambare\ContactBundle\Entity\ContactMessage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactMessageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        if ($options['name']) {
            $builder->add(
                'name',
                TextType::class,
                [
                    'required' => $options['name_required'],
                ]
            );
        }

        if ($options['email']) {
            $builder->add(
                'email',
                EmailType::class,
                [
                    'required' => $options['email_required'],
                ]
            );
        }

        if ($options['phone']) {
            $builder->add(
                'phone',
                TelType::class,
                [
                    'required' => $options['phone_required'],
                ]
            );
        }

        if ($options['subject']) {
            $builder->add(
                'subject',
                TextType::class,
                [
                    'required' => $options['subject_required'],
                ]
            );
        }

        if ($options['message']) {
            $builder->add(
                'message',
                TextareaType::class,
                [
                    'required' => $options['message_required'],
                ]
            );
        }

        if ($options['file']) {
            $builder->add(
                'file',
                FileType::class,
                ['required' => $options['file_required'],]
            );
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'       => ContactMessage::class,
                'name'             => true,
                'name_required'    => true,
                'email'            => true,
                'email_required'   => true,
                'phone'            => true,
                'phone_required'   => false,
                'subject'          => true,
                'subject_required' => true,
                'message'          => true,
                'message_required' => true,
                'file'             => false,
                'file_required'    => false,
            ]
        );
    }
}
