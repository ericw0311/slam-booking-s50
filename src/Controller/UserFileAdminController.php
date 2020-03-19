<?php
namespace App\Controller;

use App\Entity\File;

use App\Api\AdministrationApi;

use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;

class UserFileAdminController extends EasyAdminController
{
    // Interrogation des utilisateurs dossiers en filtrant sur le dossier en cours
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
}
