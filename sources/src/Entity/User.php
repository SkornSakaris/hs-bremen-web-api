<?php
/**
 * Created by PhpStorm.
 * User: LetoAtreides
 * Date: 23.05.2016
 * Time: 22:21
 */

namespace HsBremen\WebApi\Entity;


use Symfony\Component\Security\Core\User\AdvancedUserInterface;

class User implements \JsonSerializable, AdvancedUserInterface
{
    private $id;
    private $username;
    private $salt;
    private $password;
    private $roles;

    function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'salt' => $this->salt,
            'password' => $this->password,
            'roles' => $this->roles,
        ];
    }

    /** {@inheritdoc} */
    public function isAccountNonExpired()
    {
        // TODO: Implement isAccountNonExpired() method.
    }

    /** {@inheritdoc} */
    public function isAccountNonLocked()
    {
        // TODO: Implement isAccountNonLocked() method.
    }

    /** {@inheritdoc} */
    public function isCredentialsNonExpired()
    {
        // TODO: Implement isCredentialsNonExpired() method.
    }

    /** {@inheritdoc} */
    public function isEnabled()
    {
        // TODO: Implement isEnabled() method.
    }

    /** {@inheritdoc} */
    public function getRoles()
    {
        // TODO: Implement getRoles() method.
    }

    /** {@inheritdoc} */
    public function getPassword()
    {
        // TODO: Implement getPassword() method.
    }

    /** {@inheritdoc} */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /** {@inheritdoc} */
    public function getUsername()
    {
        // TODO: Implement getUsername() method.
    }

    /** {@inheritdoc} */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}