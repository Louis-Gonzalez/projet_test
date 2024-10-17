<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait DatetimeTrait {
    
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    
    #[ORM\PrePersit]
    public function autoCreatedAt(): static
    {
        if (!$this->createdAt){
            $this->createdAt = new \DateTimeImmutable();
        } 
        return $this;
    }

    #[ORM\PreUpdate]
    public function autoUpdatedAt(): static
    {
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }
}