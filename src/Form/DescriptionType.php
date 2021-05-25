<?php

namespace App\Form;

use App\Entity\Description;
use App\Repository\StockRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DescriptionType extends AbstractType
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
            ->add('logo', null, ['attr' => ['class' => 'form-control']])
            ->add('listdate', null, ['attr' => ['class' => 'form-control']])
            ->add('cik', null, ['attr' => ['class' => 'form-control']])
            ->add('bloomberg', null, ['attr' => ['class' => 'form-control']])
            ->add('figi', null, ['attr' => ['class' => 'form-control']])
            ->add('lei', null, ['attr' => ['class' => 'form-control']])
            ->add('sic', null, ['attr' => ['class' => 'form-control']])
            ->add('country', null, ['attr' => ['class' => 'form-control']])
            ->add('industry', null, ['attr' => ['class' => 'form-control']])
            ->add('sector', null, ['attr' => ['class' => 'form-control']])
            ->add('marketcap', null, ['attr' => ['class' => 'form-control']])
            ->add('employees', null, ['attr' => ['class' => 'form-control']])
            ->add('phone', null, ['attr' => ['class' => 'form-control']])
            ->add('ceo', null, ['attr' => ['class' => 'form-control']])
            ->add('url', null, ['attr' => ['class' => 'form-control']])
            ->add('description', TextareaType::class, ['attr' => ['class' => 'form-control']])
            ->add('exchange', null, ['attr' => ['class' => 'form-control']])
            ->add('name', null, ['attr' => ['class' => 'form-control']])
            ->add('symbol', null, ['attr' => ['class' => 'form-control']])
            ->add('exchangeSymbol', null, ['attr' => ['class' => 'form-control']])
            ->add('hq_address', null, ['attr' => ['class' => 'form-control']])
            ->add('hq_state', null, ['attr' => ['class' => 'form-control']])
            ->add('hq_country', null, ['attr' => ['class' => 'form-control']])
            ->add('type', null, ['attr' => ['class' => 'form-control']])
            ->add('updated', null, ['widget' => 'single_text', 'attr' => ['class' => 'form-control']])
            ->add('tags', null, ['attr' => ['class' => 'form-control']])
            ->add('similar', null, ['attr' => ['class' => 'form-control']])
            ->add('active', null, ['attr' => ['class' => 'custom-control-input']])
            ->add('stock', ChoiceType::class, ['choices' => $this->repository->findAll(), 'choice_label' => "symbol", 'placeholder' => "select Stock", 'choice_value' => 'id', 'attr' => ['class' => 'form-control']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Description::class,
        ]);
    }
}
