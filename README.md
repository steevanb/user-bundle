[![version](https://img.shields.io/badge/version-dev-red.svg)](https://github.com/steevanb/user-bundle)

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
                default_target_path: index
                username_parameter: login[username]
                password_parameter: login[password]
                csrf_parameter: login[_token]
            logout:
                path: logout
    access_control:
        - { path: ^/(fr|en)/my-account, roles: ROLE_USER }
```

```yml
# config/routes.yaml
register:
    path: /register
    controller: App\Controller\SecurityController::register

login:
    path: /login
    controller: App\Controller\SecurityController::login

logout:
    path: /logout
```
