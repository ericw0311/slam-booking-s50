<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields="email", message="user.email.already.exists")
 * @UniqueEntity(fields="username", message="user.name.already.exists")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $accountType = "INDIVIDUAL";

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
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\File", mappedBy="user")
     */
    private $files;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserParameter", mappedBy="user", orphanRemoval=true)
     */
    private $userParameters;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FileParameter", mappedBy="user", orphanRemoval=true)
     */
    private $fileParameters;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserFile", mappedBy="user", orphanRemoval=true)
     */
    private $userFiles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserFile", mappedBy="account")
     */
    private $accountUserFiles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Timetable", mappedBy="user", orphanRemoval=true)
     */
    private $timetables;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TimetableLine", mappedBy="user", orphanRemoval=true)
     */
    private $timetableLines;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserFileGroup", mappedBy="user", orphanRemoval=true)
     */
    private $userFileGroups;

    public function __construct()
    {
        $this->files = new ArrayCollection();
        $this->userParameters = new ArrayCollection();
        $this->fileParameters = new ArrayCollection();
        $this->userFiles = new ArrayCollection();
        $this->accountUserFiles = new ArrayCollection();
        $this->timetables = new ArrayCollection();
        $this->timetableLines = new ArrayCollection();
        $this->userFileGroups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getFirstAndLastName()
    {
        if ($this->getFirstName() == 'X' and $this->getLastName() == 'X') { // Urilisat...
            return $this->getUserName();
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
     * @return Collection|File[]
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(File $file): self
    {
        if (!$this->files->contains($file)) {
            $this->files[] = $file;
            $file->setUser($this);
        }

        return $this;
    }

    public function removeFile(File $file): self
    {
        if ($this->files->contains($file)) {
            $this->files->removeElement($file);
            // set the owning side to null (unless already changed)
            if ($file->getUser() === $this) {
                $file->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserParameter[]
     */
    public function getUserParameters(): Collection
    {
        return $this->userParameters;
    }

    public function addUserParameter(UserParameter $userParameter): self
    {
        if (!$this->userParameters->contains($userParameter)) {
            $this->userParameters[] = $userParameter;
            $userParameter->setUser($this);
        }

        return $this;
    }

    public function removeUserParameter(UserParameter $userParameter): self
    {
        if ($this->userParameters->contains($userParameter)) {
            $this->userParameters->removeElement($userParameter);
            // set the owning side to null (unless already changed)
            if ($userParameter->getUser() === $this) {
                $userParameter->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FileParameter[]
     */
    public function getFileParameters(): Collection
    {
        return $this->fileParameters;
    }

    public function addFileParameter(FileParameter $fileParameter): self
    {
        if (!$this->fileParameters->contains($fileParameter)) {
            $this->fileParameters[] = $fileParameter;
            $fileParameter->setUser($this);
        }

        return $this;
    }

    public function removeFileParameter(FileParameter $fileParameter): self
    {
        if ($this->fileParameters->contains($fileParameter)) {
            $this->fileParameters->removeElement($fileParameter);
            // set the owning side to null (unless already changed)
            if ($fileParameter->getUser() === $this) {
                $fileParameter->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserFile[]
     */
    public function getUserFiles(): Collection
    {
        return $this->userFiles;
    }

    public function addUserFile(UserFile $userFile): self
    {
        if (!$this->userFiles->contains($userFile)) {
            $this->userFiles[] = $userFile;
            $userFile->setUser($this);
        }

        return $this;
    }

    public function removeUserFile(UserFile $userFile): self
    {
        if ($this->userFiles->contains($userFile)) {
            $this->userFiles->removeElement($userFile);
            // set the owning side to null (unless already changed)
            if ($userFile->getUser() === $this) {
                $userFile->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserFile[]
     */
    public function getAccountUserFiles(): Collection
    {
        return $this->accountUserFiles;
    }

    public function addAccountUserFile(UserFile $accountUserFile): self
    {
        if (!$this->accountUserFiles->contains($accountUserFile)) {
            $this->accountUserFiles[] = $accountUserFile;
            $accountUserFile->setAccount($this);
        }

        return $this;
    }

    public function removeAccountUserFile(UserFile $accountUserFile): self
    {
        if ($this->accountUserFiles->contains($accountUserFile)) {
            $this->accountUserFiles->removeElement($accountUserFile);
            // set the owning side to null (unless already changed)
            if ($accountUserFile->getAccount() === $this) {
                $accountUserFile->setAccount(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Timetable[]
     */
    public function getTimetables(): Collection
    {
        return $this->timetables;
    }

    public function addTimetable(Timetable $timetable): self
    {
        if (!$this->timetables->contains($timetable)) {
            $this->timetables[] = $timetable;
            $timetable->setUser($this);
        }

        return $this;
    }

    public function removeTimetable(Timetable $timetable): self
    {
        if ($this->timetables->contains($timetable)) {
            $this->timetables->removeElement($timetable);
            // set the owning side to null (unless already changed)
            if ($timetable->getUser() === $this) {
                $timetable->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TimetableLine[]
     */
    public function getTimetableLines(): Collection
    {
        return $this->timetableLines;
    }

    public function addTimetableLine(TimetableLine $timetableLine): self
    {
        if (!$this->timetableLines->contains($timetableLine)) {
            $this->timetableLines[] = $timetableLine;
            $timetableLine->setUser($this);
        }

        return $this;
    }

    public function removeTimetableLine(TimetableLine $timetableLine): self
    {
        if ($this->timetableLines->contains($timetableLine)) {
            $this->timetableLines->removeElement($timetableLine);
            // set the owning side to null (unless already changed)
            if ($timetableLine->getUser() === $this) {
                $timetableLine->setUser(null);
            }
        }

        return $this;
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
            $userFileGroup->setUser($this);
        }

        return $this;
    }

    public function removeUserFileGroup(UserFileGroup $userFileGroup): self
    {
        if ($this->userFileGroups->contains($userFileGroup)) {
            $this->userFileGroups->removeElement($userFileGroup);
            // set the owning side to null (unless already changed)
            if ($userFileGroup->getUser() === $this) {
                $userFileGroup->setUser(null);
            }
        }

        return $this;
    }
}
