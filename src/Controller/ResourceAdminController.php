<?php
namespace App\Controller;

use App\Entity\File;
use App\Entity\ResourceClassification;
use App\Entity\Resource;

use App\Controller\AdminController;

use App\Api\AdministrationApi;

use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;

class ResourceAdminController extends EasyAdminController
{
	// Interrogation des grilles haoraires en filtrant sur le dossier en cours
	protected function createListQueryBuilder($entityClass, $sortDirection, $sortField = null, $dqlFilter = null)
    {
	$em = $this->getDoctrine()->getManager();
	$fRepository = $em->getRepository(File::class);
	// On récupère l'ID du dossier en cours
	$currentFileID = AdministrationApi::getCurrentFileID($em, $this->getUser());

	$result = parent::createListQueryBuilder($entityClass, $sortDirection, $sortField, $dqlFilter);
	$result->andWhere('entity.file = :file')->setParameter('file', $fRepository->find($currentFileID));
	return $result;
    }


 protected function createSearchQueryBuilder($entityClass, $searchQuery, array $searchableFields, $sortField = null, $sortDirection = null, $dqlFilter = null)
    {
	$em = $this->getDoctrine()->getManager();
	$fRepository = $em->getRepository(File::class);
	// On récupère l'ID du dossier en cours
	$currentFileID = AdministrationApi::getCurrentFileID($em, $this->getUser());

$result = parent::createSearchQueryBuilder($entityClass, $searchQuery, $searchableFields, $sortField, $sortDirection, $dqlFilter);
	$result->andWhere('entity.file = :file')->setParameter('file', $fRepository->find($currentFileID));
	return $result;
    }

    /**
     * Creates a new object of the current managed entity.
     *
     * @return object
     */
    protected function createNewEntity()
    {
	$em = $this->getDoctrine()->getManager();
	$fRepository = $em->getRepository(File::class);
	// On récupère l'ID du dossier en cours
	$currentFileID = AdministrationApi::getCurrentFileID($em, $this->getUser());

	return new Resource($this->getUser(), $fRepository->find($currentFileID));
    }
}
