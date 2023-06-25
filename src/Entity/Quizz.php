<?php

namespace App\Entity;

use App\Repository\QuizzRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizzRepository::class)]
class Quizz
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $theme = null;

    #[ORM\Column(length: 100)]
    private ?string $title = null;

    #[ORM\OneToMany(mappedBy: 'quizz', targetEntity: Questions::class)]
    private Collection $possede;

    public function __construct()
    {
        $this->possede = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(string $theme): static
    {
        $this->theme = $theme;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, Questions>
     */
    public function getPossede(): Collection
    {
        return $this->possede;
    }

    public function addPossede(Questions $possede): static
    {
        if (!$this->possede->contains($possede)) {
            $this->possede->add($possede);
            $possede->setQuizz($this);
        }

        return $this;
    }

    public function removePossede(Questions $possede): static
    {
        if ($this->possede->removeElement($possede)) {
            // set the owning side to null (unless already changed)
            if ($possede->getQuizz() === $this) {
                $possede->setQuizz(null);
            }
        }

        return $this;
    }
}
