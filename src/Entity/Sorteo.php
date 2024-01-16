<?php

namespace App\Entity;

use App\Repository\SorteoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SorteoRepository::class)]
class Sorteo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $sorteoDate = null;

    #[ORM\Column]
    private ?int $couponPrice = null;

    #[ORM\Column]
    private ?int $totalCoupons = null;

    #[ORM\Column]
    private ?int $prize = null;

    #[ORM\OneToMany(mappedBy: 'sorteo', targetEntity: Coupon::class)]
    private Collection $coupons;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Coupon $winnerCoupon = null;

    public function __construct()
    {
        $this->coupons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSorteoDate(): ?\DateTimeInterface
    {
        return $this->sorteoDate;
    }

    public function setSorteoDate(\DateTimeInterface $sorteoDate): static
    {
        $this->sorteoDate = $sorteoDate;

        return $this;
    }

    public function getCouponPrice(): ?int
    {
        return $this->couponPrice;
    }

    public function setCouponPrice(int $couponPrice): static
    {
        $this->couponPrice = $couponPrice;

        return $this;
    }

    public function getTotalCoupons(): ?int
    {
        return $this->totalCoupons;
    }

    public function setTotalCoupons(int $totalCoupons): static
    {
        $this->totalCoupons = $totalCoupons;

        return $this;
    }

    public function getPrize(): ?int
    {
        return $this->prize;
    }

    public function setPrize(int $prize): static
    {
        $this->prize = $prize;

        return $this;
    }

    /**
     * @return Collection<int, Coupon>
     */
    public function getCoupons(): Collection
    {
        return $this->coupons;
    }

    public function addCoupon(Coupon $coupon): static
    {
        if (!$this->coupons->contains($coupon)) {
            $this->coupons->add($coupon);
            $coupon->setSorteo($this);
        }

        return $this;
    }

    public function removeCoupon(Coupon $coupon): static
    {
        if ($this->coupons->removeElement($coupon)) {
            // set the owning side to null (unless already changed)
            if ($coupon->getSorteo() === $this) {
                $coupon->setSorteo(null);
            }
        }

        return $this;
    }

    public function getWinnerCoupon(): ?Coupon
    {
        return $this->winnerCoupon;
    }

    public function setWinnerCoupon(?Coupon $winnerCoupon): static
    {
        $this->winnerCoupon = $winnerCoupon;

        return $this;
    }
}
