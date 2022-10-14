<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class PostType extends AbstractType
{
    private TranslatorInterface $translator;
    function __construct(TranslatorInterface $translator) {
        $this->translator = $translator;
    }

    public function buildForm (FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add($this->translator->trans('subject'), TextType::class)
            ->add('content', TextareaType::class)
            ->add('save', SubmitType::class);
    }
}