<?php
namespace AppBundle\Entity;
use Doctrine\Component\Form\AbstractType;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;



class ElementFormationType extends AbstractType
{
public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder->add('phoneNumber');

}

public function setDefaultOptions(OptionsResolverInterface $resolver)
{
    $resolver->setDefaults(array(
        'data_class' => 'Acme\FormBundle\Entity\ElementFormation',
        ));
}

public function getName()
{
    return 'elementFormation';
}
}
