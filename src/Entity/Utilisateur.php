<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UtilisateurRepository;
// UniqueEntity permet de vérifier que le username est unique
use Symfony\Component\Validator\Constraints as Assert;
// PasswordEncoder permet de hasher le mot de passe
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
// on ajoute la contrainte d'unicité sur le champ username //
#[UniqueEntity(fields: ['username'], message: 'Ce nom d\'utilisateur est déjà utilisé')]
class Utilisateur implements PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    // on va ajouter un atribu pour la vérification du mot de passe //
    // on ajoute une contrainte pour la vérification du mot de passe //
    #[Assert\EqualTo(propertyPath: 'password', message: 'Les mots de passe ne sont pas identiques')]
    private ?string $verifPassword; 

    #[ORM\Column(length: 255)]
    private ?string $roles = null;
   
  
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles() 
    {
         return [$this->roles];                
       
    }
    // public function getRoles(): array
    // {
    //     $roles = $this->roles;
    //     // guarantee every user at least has ROLE_USER
    //     $roles[] = 'ROLE_USER';

    //     return array_unique($roles);
    // }

    public function setRoles(string $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get the value of verifPassword
     */ 
    public function getVerifPassword()
    {
        return $this->verifPassword;
    }

    /**
     * Set the value of verifPassword
     *
     * @return  self
     */ 
    public function setVerifPassword($verifPassword)
    {
        $this->verifPassword = $verifPassword;

        return $this;
    }

    // on ajoute la méthode pour la vérification du mot de passe
    public function eraseCredentials()
    {

    }
    public function getSalt()
    {

    }
}
