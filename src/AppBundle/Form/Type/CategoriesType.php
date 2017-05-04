<?php
namespace AppBundle\Form\Type;
use Symfony\Component\Form\AbstractType;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use AppBundle\Entity\ElementFormation;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ElementFormationType extends AbstractType
{

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
               'data_class' => CategoriesFormation::class,
           ));
  }




public function buildForm(FormBuilderInterface $builder, array $options)
{
  $builder
  ->add('label', ChoiceType::class, array('label' => 'Sigle ', 'attr' => array('placeholder' => 'sigle d’une UE, label d’un stage,', 'class' => 'form-control')));

}



}
