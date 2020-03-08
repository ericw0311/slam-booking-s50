<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use App\Validator\TimetableLineBeginningTime;
use App\Validator\TimetableLineEndTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TimetableLineRepository")
 * @ORM\HasLifecycleCallbacks()
 * @TimetableLineBeginningTime()
 * @TimetableLineEndTime()
 */
class TimetableLine
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Choice({"T", "D", "AM", "PM"})
     */
    private $type;

    /**
     * @ORM\Column(type="time")
     */
    private $beginningTime;

    /**
     * @ORM\Column(type="time")
     */
    private $endTime;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Timetable", inversedBy="timetableLines")
     * @ORM\JoinColumn(nullable=false)
     */
    private $timetable;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="timetableLines")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getBeginningTime(): ?\DateTimeInterface
    {
        return $this->beginningTime;
    }

    public function setBeginningTime(\DateTimeInterface $beginningTime): self
    {
        $this->beginningTime = $beginningTime;
        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeInterface $endTime): self
    {
        $this->endTime = $endTime;
        return $this;
    }

    public function getTimetable(): ?Timetable
    {
        return $this->timetable;
    }

    public function setTimetable(?Timetable $timetable): self
    {
        $this->timetable = $timetable;
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

    public function __construct(?User $user, ?Timetable $timetable)
    {
        $this->setUser($user);
        $this->setTimetable($timetable);
    }

    /**
     * @Assert\IsTrue(message="timetableLine.endTime.control")
     */
    public function isEndTime()
    {
        $interval = date_diff($this->getEndTime(), $this->getBeginningTime());
        return ($interval->format("%R") == "-");
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
}
