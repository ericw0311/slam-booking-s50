<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="uk_file",columns={"user_id", "name"})})
 * @ORM\Entity(repositoryClass="App\Repository\FileRepository")
 * @UniqueEntity(fields={"user", "name"}, errorPath="name", message="file.already.exists")
 * @ORM\HasLifecycleCallbacks()
 */
class File
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
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="files")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FileParameter", mappedBy="file", orphanRemoval=true)
     */
    private $fileParameters;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserFile", mappedBy="file", orphanRemoval=true)
     */
    private $userFiles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Timetable", mappedBy="file", orphanRemoval=true)
     */
    private $timetables;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserFileGroup", mappedBy="file", orphanRemoval=true)
     */
    private $userFileGroups;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Label", mappedBy="file", orphanRemoval=true)
     */
    private $labels;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Site", mappedBy="file", orphanRemoval=true)
     */
    private $sites;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ResourceClassification", mappedBy="file", orphanRemoval=true)
     */
    private $resourceClassifications;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Resource", mappedBy="file", orphanRemoval=true)
     */
    private $resources;

    public function __construct(?User $user)
    {
        $this->setUser($user);
        $this->fileParameters = new ArrayCollection();
        $this->userFiles = new ArrayCollection();
        $this->timetables = new ArrayCollection();
        $this->userFileGroups = new ArrayCollection();
        $this->labels = new ArrayCollection();
        $this->sites = new ArrayCollection();
        $this->resourceClassifications = new ArrayCollection();
        $this->resources = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
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
            $fileParameter->setFile($this);
        }
        return $this;
    }

    public function removeFileParameter(FileParameter $fileParameter): self
    {
        if ($this->fileParameters->contains($fileParameter)) {
            $this->fileParameters->removeElement($fileParameter);
            // set the owning side to null (unless already changed)
            if ($fileParameter->getFile() === $this) {
                $fileParameter->setFile(null);
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
            $userFile->setFile($this);
        }
        return $this;
    }

    public function removeUserFile(UserFile $userFile): self
    {
        if ($this->userFiles->contains($userFile)) {
            $this->userFiles->removeElement($userFile);
            // set the owning side to null (unless already changed)
            if ($userFile->getFile() === $this) {
                $userFile->setFile(null);
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
            $timetable->setFile($this);
        }

        return $this;
    }

    public function removeTimetable(Timetable $timetable): self
    {
        if ($this->timetables->contains($timetable)) {
            $this->timetables->removeElement($timetable);
            // set the owning side to null (unless already changed)
            if ($timetable->getFile() === $this) {
                $timetable->setFile(null);
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
            $userFileGroup->setFile($this);
        }

        return $this;
    }

    public function removeUserFileGroup(UserFileGroup $userFileGroup): self
    {
        if ($this->userFileGroups->contains($userFileGroup)) {
            $this->userFileGroups->removeElement($userFileGroup);
            // set the owning side to null (unless already changed)
            if ($userFileGroup->getFile() === $this) {
                $userFileGroup->setFile(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Label[]
     */
    public function getLabels(): Collection
    {
        return $this->labels;
    }

    public function addLabel(Label $label): self
    {
        if (!$this->labels->contains($label)) {
            $this->labels[] = $label;
            $label->setFile($this);
        }

        return $this;
    }

    public function removeLabel(Label $label): self
    {
        if ($this->labels->contains($label)) {
            $this->labels->removeElement($label);
            // set the owning side to null (unless already changed)
            if ($label->getFile() === $this) {
                $label->setFile(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Site[]
     */
    public function getSites(): Collection
    {
        return $this->sites;
    }

    public function addSite(Site $site): self
    {
        if (!$this->sites->contains($site)) {
            $this->sites[] = $site;
            $site->setFile($this);
        }

        return $this;
    }

    public function removeSite(Site $site): self
    {
        if ($this->sites->contains($site)) {
            $this->sites->removeElement($site);
            // set the owning side to null (unless already changed)
            if ($site->getFile() === $this) {
                $site->setFile(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ResourceClassification[]
     */
    public function getResourceClassifications(): Collection
    {
        return $this->resourceClassifications;
    }

    public function addResourceClassification(ResourceClassification $resourceClassification): self
    {
        if (!$this->resourceClassifications->contains($resourceClassification)) {
            $this->resourceClassifications[] = $resourceClassification;
            $resourceClassification->setFile($this);
        }

        return $this;
    }

    public function removeResourceClassification(ResourceClassification $resourceClassification): self
    {
        if ($this->resourceClassifications->contains($resourceClassification)) {
            $this->resourceClassifications->removeElement($resourceClassification);
            // set the owning side to null (unless already changed)
            if ($resourceClassification->getFile() === $this) {
                $resourceClassification->setFile(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Resource[]
     */
    public function getResources(): Collection
    {
        return $this->resources;
    }

    public function addResource(Resource $resource): self
    {
        if (!$this->resources->contains($resource)) {
            $this->resources[] = $resource;
            $resource->setFile($this);
        }

        return $this;
    }

    public function removeResource(Resource $resource): self
    {
        if ($this->resources->contains($resource)) {
            $this->resources->removeElement($resource);
            // set the owning side to null (unless already changed)
            if ($resource->getFile() === $this) {
                $resource->setFile(null);
            }
        }

        return $this;
    }
}
