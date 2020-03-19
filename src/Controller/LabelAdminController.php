<?php
namespace App\Controller;

use App\Entity\File;
use App\Entity\Label;

use App\Api\AdministrationApi;

use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;

class LabelAdminController extends EasyAdminController
{
	// Interrogation des étiquettes en filtrant sur le dossier en cours
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

	return new Label($this->getUser(), $fRepository->find($currentFileID));
    }
/*
    protected function persistEntity($entity)
    {
	$em = $this->getDoctrine()->getManager();
	$fRepository = $em->getRepository(File::class);

	$entity->setUser($this->getUser());

	// On récupère l'ID du dossier en cours
	$currentFileID = AdministrationApi::getCurrentFileID($em, $this->getUser());
	$entity->setFile($fRepository->find($currentFileID));

	parent::persistEntity($entity);
    }
 */
}
