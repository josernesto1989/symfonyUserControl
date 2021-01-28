<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Venta;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VentaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('descripcion')
            ->add('hora',null,['attr' => ['class' => 'hiddenew' ]])
//            ->add('fecha')
            ->add('trabajo')
            ->add('ingreso',NumberType::class,[ 'attr' => ['class' => 'number', 'type'=> 'number']])
            ->add('costo',null,[ 'attr' => ['class' => 'number']])
            ->add('creditos',null,[ 'attr' => ['class' => 'number']])
            ->add('user', EntityType::class,
                ['class' => User::class,
                    'choice_label' => 'username',
                    'attr' => [
                        'data-live-search'=>'true',
                        'class' => 'selectpicker'
                        ]
                    ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Venta::class,
        ]);
    }
}
