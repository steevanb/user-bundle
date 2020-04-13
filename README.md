[![version](https://img.shields.io/badge/version-dev-red.svg)](https://github.com/steevanb/user-bundle)

# Installation

### Add dependency

```bash
composer require steevanb/user-bundle 0.0.*
# If you want to validate User entity data
composer require symfony/validator symfony/translation
```

### Add bundle

```php
# config/bundles.php
return [
    Symfony\Bundle\SecurityBundle\SecurityBundle::class => ['all' => true],
    steevanb\\UserBundle\UserBundle::class => ['all' => true]
]
```

### Configure security

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
        - { path: ^/secured-area, roles: ROLE_USER }
```

### Create User entity

Create the entity:
```php
# src/Entity/User.php

namespace App\Entity;

use steevanb\UserBundle\Entity\AbstractUser;

class User extends AbstractUser
{
}

```

Create the mapping:
```yaml
#config/doctrine/User.orm.yml

App\Entity\User:
    type: entity
    table: user

    id:
        id:
            type: integer
            generator: { strategy: AUTO }
            options: { unsigned: true }

    fields:
        username:
            length: 50

        password:
            length: 64

        email:
            unique: true

        roles:
            type: array

        createdAt:
            type: datetime
```

### Create SecurityController

```php
# src/Controller/SecurityController.php
class SecurityController extends steevanb\UserBundle\Controller\AbstractSecurityController
{
    protected function createUser(): AbstractUser
    {
        # Create your User entity
        return new User();
    }

    protected function createRegisterForm(): FormInterface
    {
        return new RegisterType();
    }

    protected function createRegisteredResponse(): Response
    {
        return new Response('User registered.');
    }
}
```

### Login

Add route:
```yml
# config/routes.yaml
login:
    path: /login
    controller: App\Controller\SecurityController::login
```

Create template:
```twig
{# templates/Security/login.html.twig #}

{% if error is not null %}
    <div class="alert alert-danger">{{ error }}</div>
{% endif %}

{{ form(formView) }}
```

### Logout

Add route:
```yml
logout:
    path: /logout
```

# Enable registration

Registration is not needed and not enabled by default.

Add route:
```yml
# config/routes.yaml
register:
    path: /register
    controller: App\Controller\SecurityController::register
```

Create RegisterType:
```php
# src/Form/Type/RegisterType.php

namespace App\Form\Type;

use steevanb\UserBundle\Form\Type\AbstractRegisterType;

class RegisterType extends AbstractRegisterType
{
}
```
