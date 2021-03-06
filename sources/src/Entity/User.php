<?php

namespace HsBremen\WebApi\Entity;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Swagger\Annotations as SWG;

/**
 * Class User
 *
 * @package HsBremen\WebApi\Entity
 * @SWG\Definition(
 *     definition="user",
 *     type="object"
 * )
 */
class User implements \JsonSerializable, AdvancedUserInterface
{
    /**
     * @var int $id
     * @SWG\Property(type="integer", format="int32", description="Die interne Id des Users")
     */
    private $id;
    private $username;
    private $salt;
    private $password;
    private $enabled;
    private $accountNonExpired;
    private $credentialsNonExpired;
    private $accountNonLocked;
    private $roles;

    public function __construct($id = null, $username, $password, array $roles = array(), $enabled = true, $userNonExpired = true, $credentialsNonExpired = true, $userNonLocked = true)
    {
        if ('' === $username || null === $username) {
            throw new \InvalidArgumentException('The username cannot be empty.');
        }

        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->enabled = $enabled;
        $this->accountNonExpired = $userNonExpired;
        $this->credentialsNonExpired = $credentialsNonExpired;
        $this->accountNonLocked = $userNonLocked;
        $this->roles = $roles;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'password' => $this->password,
            'enabled' => $this->enabled,
            'accountNonExpired' => $this->accountNonExpired,
            'credentialsNonExpired' => $this->credentialsNonExpired,
            'accountNonLocked' => $this->accountNonLocked,
            'roles' => $this->roles,
        ];
    }

    public function __toString()
    {
        return $this->getUsername();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonExpired()
    {
        return $this->accountNonExpired;
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonLocked()
    {
        return $this->accountNonLocked;
    }

    /**
     * {@inheritdoc}
     */
    public function isCredentialsNonExpired()
    {
        return $this->credentialsNonExpired;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }
}