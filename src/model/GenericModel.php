<?php

namespace model;

use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
abstract class GenericModel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
