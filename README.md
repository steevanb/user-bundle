```php
# config/bundles.php
return [
    Symfony\Bundle\SecurityBundle\SecurityBundle::class => ['all' => true],
    steevanb\\UserBundle\UserBundle::class => ['all' => true]
]
```

```yml
# config/packages/security.yaml
security:
    encoders:
        App\Entity\User:
            algorithm: bcrypt
            cost: 12
    providers:
        database:
            entity:
                class: App\Entity\User
                property: username
    firewalls:
        dev:
            pattern: ^/(_(profiler|wdt)|css|images|js)/
            security: false
        main:
            anonymous: ~
            provider: database
            form_login:
                login_path: login
                check_path: login
    access_control:
        - { path: ^/(fr|en)/my-account, roles: ROLE_USER }
```

```yml
# config/routes.yaml
register:
    path: /{_locale}/register
    controller: App\Controller\SecurityController::register

login:
    path: /{_locale}/login
    controller: App\Controller\SecurityController::login
```

{{ form(form, {'attr': {'novalidate': 'novalidate'}}) }}
