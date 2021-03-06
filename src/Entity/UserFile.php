<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserFileRepository")
 * @UniqueEntity(fields={"file", "email"}, errorPath="email", message="user.already.assigned.to.file")
 * @ORM\HasLifecycleCallbacks()
 */
class UserFile
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $accountType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $uniqueName;

    /**
     * @ORM\Column(type="boolean")
     */
    private $administrator;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="boolean")
     */
    private $userCreated;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userFiles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="accountUserFiles")
     */
    private $account;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\File", inversedBy="userFiles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $file;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $resourceUser;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\UserFileGroup", mappedBy="userFiles")
     */
    private $userFileGroups;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getAccountType(): ?string
    {
        return $this->accountType;
    }

    public function setAccountType(string $accountType): self
    {
        $this->accountType = $accountType;
        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getUniqueName(): ?string
    {
        return $this->uniqueName;
    }

    public function setUniqueName(?string $uniqueName): self
    {
        $this->uniqueName = $uniqueName;
        return $this;
    }

    public function getAdministrator(): ?bool
    {
        return $this->administrator;
    }

    public function setAdministrator(bool $administrator): self
    {
        $this->administrator = $administrator;
        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;
        return $this;
    }

    public function getUserCreated(): ?bool
    {
        return $this->userCreated;
    }

    public function setUserCreated(bool $userCreated): self
    {
        $this->userCreated = $userCreated;
        return $this;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getAccount(): ?User
    {
        return $this->account;
    }

    public function setAccount(?User $account): self
    {
        $this->account = $account;
        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): self
    {
        $this->file = $file;
        return $this;
    }

    public function getResourceUser(): ?bool
    {
        return $this->resourceUser;
    }

    public function setResourceUser(bool $resourceUser): self
    {
        $this->resourceUser = $resourceUser;
        return $this;
    }

    public function __construct(?User $user, ?File $file)
    {
        $this->setUser($user);
        $this->setFile($file);
        $this->setAccountType("INDIVIDUAL");
        $this->setActive(true);
        $this->userFileGroups = new ArrayCollection();
    }

    public function getFirstAndLastName()
    {
      if ($this->getFirstName() == 'X' and $this->getLastName() == 'X') {
        if ($this->getUniqueName() != '') {
            return $this->getUniqueName();
        }
        if ($this->getUserCreated()) {
            return $this->getUserName();
        }
      }
      return $this->getFirstName().' '.$this->getLastName();
    }

    /**
    * @Assert\IsTrue(message="user.organisation.name.null")
    */
    public function isUniqueName()
    {
        return ($this->getAccountType() != 'ORGANISATION' or $this->getUniqueName() !== null);
    }

    /**
    * @ORM\PrePersist
    */
    public function createDate()
    {
      $this->createdAt = new \DateTime();
    }

    /**
    * @ORM\PreUpdate
    */
    public function updateDate()
    {
      $this->updatedAt = new \DateTime();
    }

    /**
     * @return Collection|UserFileGroup[]
     */
    public function getUserFileGroups(): Collection
    {
        return $this->userFileGroups;
    }

    public function addUserFileGroup(UserFileGroup $userFileGroup): self
    {
        if (!$this->userFileGroups->contains($userFileGroup)) {
            $this->userFileGroups[] = $userFileGroup;
            $userFileGroup->addUserFile($this);
        }

        return $this;
    }

    public function removeUserFileGroup(UserFileGroup $userFileGroup): self
    {
        if ($this->userFileGroups->contains($userFileGroup)) {
            $this->userFileGroups->removeElement($userFileGroup);
            $userFileGroup->removeUserFile($this);
        }

        return $this;
    }
}
