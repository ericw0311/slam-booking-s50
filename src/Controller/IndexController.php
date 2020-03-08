<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\UserContext;
use App\Entity\FileContext;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('index.html.twig');
    }

    /**
     * @Route("/{_locale}/home", name="home")
     */
    public function home()
    {
      $connectedUser = $this->getUser();
      $em = $this->getDoctrine()->getManager();
      $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

      if ($userContext->getCurrentFileID() <= 0) {
          return $this->render('index/index.html.twig', array('userContext' => $userContext));
      } else {
          return $this->redirectToRoute('summary');
      }
    }

    /**
     * @Route("/{_locale}/default/summary", name="summary")
     */
    public function summary()
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur
    $fileContext = new FileContext($em, $userContext); // contexte dossier
/* TPRR
    $iRepository = $em->getRepository(Innovation::class);
    $iufRepository = $em->getRepository(InnovationUserFile::class);

    if ($userContext->getCurrentUserFileAdministrator()) { // L'utilisateur est adminsitrateur du dossier
      $innovations = $iRepository->getUnreadInnovations($iufRepository->getUserFileInnovationQB($userContext->getCurrentUserFile()));
    } else { // L'utilisateur n'est pas adminsitrateur du dossier
      $innovations = $iRepository->getUnreadNonAdministratorInnovations($iufRepository->getUserFileInnovationQB($userContext->getCurrentUserFile()));
    }

    $displayInnovations = (count($innovations) > 0);
*/
    $displayInnovations = false; /* TPRR */

      return $this->render('index/summary.html.twig', array('userContext' => $userContext, 'fileContext' => $fileContext,
        'displayInnovations' => $displayInnovations));
    }

    // Validation de la consultation d'une innovation
    /**
     * @Route("/{_locale}/default/validate_innovation/{innovationCode}", name="default_validate_innovation")
     */
    public function validateInnovation($innovationCode)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $iRepository = $em->getRepository(Innovation::class);
    $iufRepository = $em->getRepository(InnovationUserFile::class);

    $innovation = $iRepository->findOneBy(array('code' => $innovationCode));

    if ($innovation !== null) {
      $innovationUserFile = $iufRepository->findOneBy(array('innovation' => $innovation, 'userFile' => $userContext->getCurrentUserFile()));
      if ($innovationUserFile === null) {
        $innovationUserFile = new InnovationUserFile($innovation, $userContext->getCurrentUserFile());
        $em->persist($innovationUserFile);
        $em->flush();
      }
    }
    return $this->redirectToRoute('default_summary');
    }

    /**
     * @Route("/documentation/{pageCode}", name="documentation")
     */
    public function documentation($pageCode)
    {
        return $this->render('index/documentation.html.twig', [
            'pageCode' => $pageCode,
        ]);
    }
}
