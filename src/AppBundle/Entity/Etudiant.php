namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="cursus")
 */
class Cursus
{




  /**
   * @ORM\Column(type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
* @ORM\Column(type="array")
*/
    private $listCursus;

    /**
 * @ORM\Column(type="string", length=100)
 */
    private $label;
}
