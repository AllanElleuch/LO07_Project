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
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class ElementFormationType extends AbstractType
{

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
               'data_class' => ElementFormation::class,
           ));
  }




public function buildForm(FormBuilderInterface $builder, array $options)
{
  $builder
  ->add('sigle', TextType::class, array('label' => 'Sigle ', 'attr' => array('placeholder' => 'Ex. LO07', 'class' => 'form-control')))
  ->add('credits', IntegerType ::class, array('label' => 'Crédits ', 'attr' => array('placeholder' => 'nombre de crédits obtenus', 'class' => 'form-control', 'min' => '0')))
  ->add('sem_seq', IntegerType ::class, array('label' => 'Numéro du semestre ', 'attr' => array('placeholder' => 'numéro de semestre à l’UTT', 'class' => 'form-control', 'min' => '0')))
  ->add('sem_label', TextType::class, array('label' => 'Label du semestre', 'attr' => array('placeholder' => 'ISIx',
      'class' => 'form-control ')))
  ->add('affectations', EntityType::class, array(
      'class' => 'AppBundle:Affectations',
      'choice_label' => 'label',
      'label' => 'Affectation'))
  ->add('categories', EntityType::class, array(
      'class' => 'AppBundle:Categories',
      'choice_label' => 'label',
      'label' => 'Catégorie'))
  ->add('resultats', EntityType::class, array(
      'class' => 'AppBundle:Resultats',
      'choice_label' => 'label',
      'label' => 'Résultat'))
  ->add('utt', CheckboxType::class, array('label' => "UE suivie à l'UTT",'required' => false, 'attr' => array(
      'class' => 'form-control',
      'checked' => 'checked',
      )))
  ->add('profil', CheckboxType::class, array('label' => 'UE de profil','required' => false, 'attr' => array(
      'class' => 'form-control',
      'checked' => 'checked')
  ));


}



}
