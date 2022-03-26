<?php

namespace App\Entity;

use App\Repository\FigureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as FigureAssert;

/**
 * @ORM\Entity(repositoryClass=FigureRepository::class)
 * @UniqueEntity(
 *     fields={"name"},
 *     message="validator.figure.exist"
 * )
 * @FigureAssert\SlugUnicityClass(mode="strict")
 */
class Figure
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank(message="validator.notblank")
     * @Assert\Length(
     *      min = 2,
     *      max = 200,
     *      minMessage = "validator.figure.name.length.min",
     *      maxMessage = "figure.name.length.max"
     * )
     */
    private string $name;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="validator.notblank")
     * @Assert\Length(
     *      min = 20,
     *      max = 5000,
     *      minMessage = "figure.description.length.min",
     *      maxMessage = "figure.description.length.max"
     * )
     */
    private string $description;

    /**
     * @ORM\Column(type="string", length=255,unique=true)
     *
     */
    private string $slug;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private \DateTimeImmutable|null $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="figure", orphanRemoval=true)
     * @Assert\Valid()
     */
    private Collection $images;

    /**
     * @ORM\OneToMany(targetEntity=Video::class, mappedBy="figure", orphanRemoval=true)
     * @Assert\Valid()
     */
    private Collection $videos;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="figure", orphanRemoval=true)
     */
    private Collection $messages;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="figures")
     * @ORM\JoinColumn(nullable=false)
     */
    private User|null $user;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="figures")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="notblank.category")
     */
    private Category|null $category;

    public function __construct()
    {
        $this->id = null;
        $this->images = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->messages = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setFigure($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getFigure() === $this) {
                $image->setFigure(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Video[]
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Video $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setFigure($this);
        }

        return $this;
    }

    public function removeVideo(Video $video): self
    {
        if ($this->videos->removeElement($video)) {
            // set the owning side to null (unless already changed)
            if ($video->getFigure() === $this) {
                $video->setFigure(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setFigure($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getFigure() === $this) {
                $message->setFigure(null);
            }
        }

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function __toString(): string
    {
        if ($this->name == null) {
            return '';
        }

        return $this->name;
    }
}
