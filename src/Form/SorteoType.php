<?php

namespace App\Form;

use App\Entity\Coupon;
use App\Entity\Sorteo;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SorteoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('sorteoDate')
            ->add('couponPrice')
            ->add('totalCoupons')
            ->add('prize')
//             ->add('winnerCoupon', EntityType::class, [
//                 'class' => Coupon::class,
// 'choice_label' => 'id',
//             ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sorteo::class,
        ]);
    }
}
