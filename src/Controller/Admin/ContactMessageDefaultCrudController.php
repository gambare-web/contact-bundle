<?php


namespace Gambare\ContactBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Gambare\ContactBundle\Entity\ContactMessage;

class ContactMessageDefaultCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ContactMessage::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Message')
            ->setEntityLabelInPlural('Messages')
            ->setSearchFields(['name', 'email', 'to', 'phone', 'subject', 'message'])
            ->setPageTitle(Crud::PAGE_INDEX, '%entity_label_plural%')
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters->add('name')->add('email')->add('to')->add('phone')->add('subject');
    }

    public function configureFields(string $pageName): iterable
    {
        $panelMessage = FormField::addPanel('Message', 'far fa-envelope');
        $name         = TextField::new('name', 'Name');
        $email        = EmailField::new('email', 'Email');
        $to           = EmailField::new('to', 'To');
        $phone        = TextField::new('phone', 'Phone');
        $subject      = TextField::new('subject', 'Subject');
        $message      = TextareaField::new('message', 'Message');
        $filename     = ImageField::new('filename', 'File')
                                  ->setBasePath('uploads/contact_message')
                                  ->setUploadDir('public/uploads/contact_message/');

        // Traits Timestampable
        $panelTime = FormField::addPanel('Time', 'fas fa-clock');
        $createdAt = DateTimeField::new('createdAt');
        $updatedAt = DateTimeField::new('updatedAt');

        $id = IntegerField::new('id', 'ID');

        $new = [
            $panelMessage,
            $name,
            $email,
            $to,
            $phone,
            $subject,
            $message,
            $filename,
        ];

        if (Crud::PAGE_INDEX === $pageName) {
            return [$name, $email, $to, $subject, $createdAt, $filename];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [
                $id,
                $name,
                $email,
                $to,
                $phone,
                $subject,
                $message,
                $filename,
                $createdAt,
                $updatedAt,
            ];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return $new;
        } elseif (Crud::PAGE_EDIT === $pageName) {
            array_push($new, $panelTime, $createdAt, $updatedAt);

            return $new;
        }

        return [];
    }
}