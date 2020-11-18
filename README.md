Contact Bundle
==========

- Contact bundle for Symfony 5 with [EasyAdminBundle v3](https://symfony.com/doc/current/bundles/EasyAdminBundle/index.html) integration.

*Note : This Bundle is intended for personal use. But you are free to use it if you really want to*

Installation
============

Open a command console, enter your project directory and execute:

```console
composer require gambare-web/contact-bundle:dev-main
```

Configuration
============

#### Activate doctrine extention

```yaml
stof_doctrine_extensions:
    orm:
        default:
            timestampable: true
```
*-TODO- automatize with a recipe*

#### Add Vich uploader config for file attachment

```yaml
# config/packages/vich_uploader.yaml
vich_uploader:
    mappings:
        message_attachement:
            uri_prefix: /message_file
            upload_destination: '%kernel.project_dir%/public/uploads/contact_message'
            namer: Vich\UploaderBundle\Naming\UniqidNamer
```

#### Generate Migration

```console
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

#### Configure Symfony Mailer

https://symfony.com/doc/current/mailer.html

configure MAILER_DSN in your .env.local (or user symfony/google-mailer on dev)


Usage
============

Copy one exemple from src/Controller/ContactMessageController

You can change the form options about the fields you want to show and have as required using the FormType resolver default

```php
$contact_form = $this->createForm(ContactMessageFormType::class, null, ['name_required' => false, 'file' => true]);
```

Twig integration
============

One default html template is included. You can use it, extend it or use your own.

- The base template is : '@gambare-web/email/base_email.html.twig'
- The demo template is : '@gambare-web/email/contact_notif.html.twig'

The following parameters are used in the template. 
```php
            $email = (new TemplatedEmail())
                ->htmlTemplate('@gambare-web/email/contact_notif.html.twig')
                ->context(
                    [
                        'name'    => $contact->getName(),
                        'subject' => $contact->getSubject(),
                        'mail'    => $contact->getEmail(),
                        'phone'     => $contact->getPhone(),
                        'message' => $contact->getMessage(),
                        'logo_url' => 'https://.../img.png',
                        'logo_name' => 'W00tKorp',
                    ]
                );
```

EasyAdminBundle v3 integration
============

Create a ContactMessageCrudController class that extends ContactMessageDefaultCrudController

ContactMessageDefaultCrudController is extending the AbstractCrudController from EasyAdmin