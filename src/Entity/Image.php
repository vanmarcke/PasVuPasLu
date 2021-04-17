<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @ORM\Table(name="image",options={"row_format":"DYNAMIC"},)
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 */
class Image
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string",nullable=true)
     */
    private $alt;
    /**
     * @ORM\Column(type="string")
     */
    private $description;

    /**
     * @var UploadedFile
     *
     * @Assert\Image(
     *     mimeTypes={"image/png", "image/jpeg"}
     *
     * )
     */
    private $file;
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('file', new Assert\Image());

        $metadata->addPropertyConstraint('description', new Assert\Type('string'));
        $metadata->addPropertyConstraint('description', new Assert\NotNull());
    }

    /**
     * @var bool
     */
    private $deletedImage;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file = null): self
    {
        $this->file = $file;

        return $this;
    }

    public function setAlt(string $alt): self
    {
        $this->alt = $alt;

        return $this;
    }

    public function getAlt(): string
    {
        return $this->alt;
    }

    /**
     * @return bool
     */
    public function isDeletedImage(): ?bool
    {
        return $this->deletedImage;
    }

    public function setDeletedImage(bool $deletedImage): void
    {
        $this->deletedImage = $deletedImage;
    }
    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function setDescription(string $description)
    {
        $this->description = $description;
    }
}
