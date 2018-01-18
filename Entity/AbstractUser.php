<?php

declare(strict_types=1);

namespace steevanb\UserBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

abstract class AbstractUser implements UserInterface, \Serializable
{
    /** @var ?int */
    protected $id;

    /** @var ?string */
    protected $username;

    /** @var ?string */
    protected $plainPassword;

    /** @var string */
    protected $plainPasswordConfirmation;

    /** @var ?string */
    protected $password;

    /** @var ?string */
    protected $email;

    /** @var array */
    protected $roles = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    /** @return $this */
    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    /** @return $this */
    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /** @return $this */
    public function setPlainPasswordConfirmation(?string $plainPasswordConfirmation): self
    {
        $this->plainPasswordConfirmation = $plainPasswordConfirmation;

        return $this;
    }

    public function getPlainPasswordConfirmation(): ?string
    {
        return $this->plainPasswordConfirmation;
    }

    /** @return $this */
    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    /** @return $this */
    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getSalt()
    {
        return null;
    }

    /** @return $this */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /** @return $this */
    public function addRole(string $role): self
    {
        if (in_array($role, $this->roles) === false) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    /** @return $this */
    public function removeRole(string $role): self
    {
        $key = array_search($role, $this->roles);
        if ($key !== false) {
            unset($this->roles[$key]);
        }

        return $this;
    }

    /** @return $this */
    public function clearRoles(): self
    {
        $this->roles = [];

        return $this;
    }

    public function eraseCredentials()
    {
    }

    public function serialize(): string
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password
        ));
    }

    public function unserialize($serialized): void
    {
        [
            $this->id,
            $this->username,
            $this->password
        ] = unserialize($serialized);
    }
}
