<?php
namespace Portrino\PxSemantic\Controller;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2017 Andre Wuttig <wuttig@portrino.de>, portrino GmbH
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use Portrino\PxSemantic\Domain\Repository\RestRepositoryInterface;
use Portrino\PxSemantic\Entity\EntityInterface;
use Portrino\PxSemantic\Hydra\Collection;
use Portrino\PxSemantic\Mvc\View\JsonLdView;
use Portrino\PxSemantic\Processor\ProcessorInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\View\JsonView;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Fluid\Exception;

/**
 * Class RestController
 *
 * @package Portrino\PxSemantic\Controller
 */
class RestController extends ActionController
{

    /**
     * @var JsonView
     */
    protected $view;

    /**
     * @var string
     */
    protected $defaultViewObjectName = JsonLdView::class;

    /**
     * Name of the action method argument which acts as the resource for the
     * RESTful controller. If an argument with the specified name is passed
     * to the controller, the show, update and delete actions can be triggered
     * automatically.
     *
     * @var string
     */
    protected $resourceArgumentName = 'uid';

    /**
     * @var string
     */
    protected $resourceType = '';

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Repository
     */
    protected $resourceRepository;

    /**
     * @var string
     */
    protected $entityClassName = '';

    /**
     * @param ViewInterface $view
     */
    protected function initializeView(ViewInterface $view)
    {
        if ($view instanceof JsonView) {

            if ($this->request->getControllerActionName() === 'list' || $this->request->getControllerActionName() === 'show') {
                $configuration = [
                    'collection' => [
                        '_only' => ['id', 'context', 'type', 'totalItems', 'member'],
                        '_descend' => [
                            'member' => [
                                '_descendAll' => [
                                    '_descend' => [
                                        'datePublished' => []
                                    ]
                                ],
                            ],
                        ]
                    ],
                    'entity' => []
                ];
                $view->setConfiguration($configuration);
            }

        }
        parent::initializeView($view);
    }


    /**
     * Determines the action method and assures that the method exists.
     *
     * @return string The action method name
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchActionException if the action specified in the request object does not exist (and if there's no default action either).
     */
    protected function resolveActionMethodName()
    {
        $httpRequest = $_SERVER['REQUEST_METHOD'];
        if ($this->request->getControllerActionName() === 'index') {
            $actionName = 'index';
            switch ($httpRequest) {
                case 'HEAD':
                case 'GET':
                    $actionName = ($this->request->hasArgument($this->resourceArgumentName)) ? 'show' : 'list';
                    break;
            }
            $this->request->setControllerActionName($actionName);
        }
        return parent::resolveActionMethodName();
    }

    protected function initializeAction()
    {
        parent::initializeAction();

        $endpoint = $this->request->hasArgument('endpoint') ? $this->request->getArgument('endpoint') : null;

        if ($endpoint === null) {
            throw new Exception('No endpoint given!', 1476453894);
        }

        /**
         * take the the className from className config of entity object, otherwise take the _typoScriptNodeValue for backwards compatibility
         */
        $this->entityClassName = isset($this->settings['rest']['endpoints'][$endpoint]['entity']) ? $this->settings['rest']['endpoints'][$endpoint]['entity'] : null;
        if (!class_exists($this->entityClassName)) {
            throw new Exception('The entity class: "' . $this->entityClassName . '" does not exist.', 1475830556);
        }

        $this->resourceType = isset($this->settings['rest']['endpoints'][$endpoint]['resource']) ? $this->settings['rest']['endpoints'][$endpoint]['resource'] : null;
        if (!class_exists($this->resourceType)) {
            throw new Exception('The resource type: "' . $this->resourceType . '" does not exist.', 1475830556);
        }

        $resourceRepositoryClass = str_replace('\Model', '\Repository', $this->resourceType) . 'Repository';
        if (!class_exists($resourceRepositoryClass)) {
            throw new Exception('The repository: "' . $resourceRepositoryClass . '" for resource of type: "' . $resourceType . '" does not exist.',
                1475830556);
        }




        /** @var \TYPO3\CMS\Extbase\Persistence\Repository $repository */
        $this->resourceRepository = $this->objectManager->get($resourceRepositoryClass);

    }

    /**
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    protected function mapRequestArgumentsToControllerArguments()
    {
        try {
            parent::mapRequestArgumentsToControllerArguments();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @param string $endpoint
     */
    public function listAction($endpoint = '')
    {
        $offset = (GeneralUtility::_GET('offset') != null) ? (int)GeneralUtility::_GET('offset') : 0;
        $limit = (GeneralUtility::_GET('limit') != null) ? (int)GeneralUtility::_GET('limit') : -1;

        if ($this->resourceRepository instanceof RestRepositoryInterface) {
            $domainObjects = $this->resourceRepository->findByOffsetAndLimitAndConstraint($offset, $limit)->toArray();
        } else {
            $domainObjects = $this->resourceRepository->findAll();
        }

        $totalItems = $this->resourceRepository->countAll();

        /** @var Collection $collection */
        $collection = $this->objectManager->get(Collection::class);

        $indexUrl = $this->uriBuilder
            ->reset()
            ->setTargetPageUid($this->settings['rest']['pid'])
            ->setTargetPageType($this->settings['rest']['typeNum'])
            ->setUseCacheHash(false)
            ->setArguments(
                [
                    'offset' => $offset,
                    'limit' => $limit
                ]
            )
            ->uriFor(
                null,
                [
                    'endpoint' => $endpoint
                ],
                null,
                null,
                null
            );

        $collection->setId($indexUrl);

        /** @var AbstractEntity $domainObject */
        foreach ($domainObjects as $domainObject) {
            /** @var EntityInterface $entity */
            $entity = $this->objectManager->get($this->entityClassName);

            foreach ($this->settings['rest']['endpoints'][$endpoint]['processors'] as $processorConfiguration) {
                /** @var ProcessorInterface $processor */
                $processor = $this->objectManager->get($processorConfiguration['className']);
                $settings = isset($processorConfiguration['settings']) ? $processorConfiguration['settings'] : [];
                $processor->process($entity, $settings, $domainObject->getUid());
            }

            $iri = $this->uriBuilder
                ->reset()
                ->setTargetPageUid($this->settings['rest']['pid'])
                ->setTargetPageType($this->settings['rest']['typeNum'])
                ->setUseCacheHash(false)
                ->uriFor(
                    null,
                    [
                        'endpoint' => $endpoint,
                        'uid' => $domainObject->getUid()
                    ],
                    null,
                    null,
                    null
                );

            $entity->setId($iri);

            $collection->addMember($entity);
        }

        $collection->setTotalItems($totalItems);

        $this->view->setVariablesToRender(['collection']);
        $this->view->assign('collection', $collection);
    }

    /**
     * @param string $endpoint
     * @param int $uid
     *
     * @throws Exception
     */
    public function showAction($endpoint = '', $uid = 0)
    {

        /** @var AbstractEntity|null $domainObject */
        $domainObject = $this->resourceRepository->findByUid($uid);

        if ($domainObject != null) {
            /** @var EntityInterface $entity */
            $entity = $this->objectManager->get($this->entityClassName);

            foreach ($this->settings['rest']['endpoints'][$endpoint]['processors'] as $processorConfiguration) {
                /** @var \Portrino\PxSemantic\Processor\ProcessorInterface $processor */
                $processor = $this->objectManager->get($processorConfiguration['className']);
                $settings = isset($processorConfiguration['settings']) ? $processorConfiguration['settings'] : [];
                $processor->process($entity, $settings, $domainObject->getUid());
            }

            $iri = $this->uriBuilder
                ->reset()
                ->setTargetPageUid($this->settings['rest']['pid'])
                ->setTargetPageType($this->settings['rest']['typeNum'])
                ->setUseCacheHash(false)
                ->uriFor(
                    null,
                    [
                        'endpoint' => $endpoint,
                        'uid' => $domainObject->getUid()
                    ],
                    null,
                    null,
                    null
                );

            $entity->setId($iri);
        }

        $this->view->setVariablesToRender(['entity']);
        $this->view->assign('entity', $entity);
    }

}