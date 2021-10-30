<?php

namespace App\Entity;

use App\Repository\ThemeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ThemeRepository::class)
 */
class Theme
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Unique()
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Unique()
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=TextTemplate::class, mappedBy="theme", orphanRemoval=true)
     */
    private $texts;

    public function __construct()
    {
        $this->texts = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getTexts(): Collection
    {
        return $this->texts;
    }

    public function addText(TextTemplate $text): self
    {
        if (!$this->texts->contains($text)) {
            $this->texts[] = $text;
            $text->setTheme($this);
        }

        return $this;
    }

    public function removeText(TextTemplate $text): self
    {
        if ($this->texts->removeElement($text)) {
            // set the owning side to null (unless already changed)
            if ($text->getTheme() === $this) {
                $text->setTheme(null);
            }
        }

        return $this;
    }
}
