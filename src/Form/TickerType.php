<?php

namespace App\Form;

use App\Entity\Ticker;
use App\Repository\StockRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TickerType extends AbstractType
{
    /**
     * @var StockRepository
     */
    private $repository;

    public function __construct(StockRepository $repository)
    {
        $this->repository = $repository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', null, ['widget' => 'single_text', 'attr' => ['class' => 'form-control']])
            ->add('open', null, ['attr' => ['class' => 'form-control']])
            ->add('hight', null, ['attr' => ['class' => 'form-control']])
            ->add('low', null, ['attr' => ['class' => 'form-control']])
            ->add('close', null, ['attr' => ['class' => 'form-control']])
            ->add('adj_close', null, ['attr' => ['class' => 'form-control']])
            ->add('volume', null, ['attr' => ['class' => 'form-control']])
            ->add('symbol', null, ['attr' => ['class' => 'form-control']])
            ->add('stock', ChoiceType::class, ['choices' => $this->repository->findAll(), 'choice_label' => "symbol", 'placeholder' => "select Stock", 'choice_value' => 'id', 'attr' => ['class' => 'form-control']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ticker::class,
        ]);
    }
}
