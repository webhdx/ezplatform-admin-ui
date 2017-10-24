<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUiBundle\Controller;

use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\Core\MVC\Symfony\View\ContentView;
use EzSystems\EzPlatformAdminUi\Form\Data\Location\LocationCopyData;
use EzSystems\EzPlatformAdminUi\Form\Data\Location\LocationMoveData;
use EzSystems\EzPlatformAdminUi\Form\Data\Location\LocationTrashData;
use EzSystems\EzPlatformAdminUi\Form\Factory\FormFactory;
use EzSystems\EzPlatformAdminUi\Service\PathService;

class ContentViewController extends Controller
{
    /** @var ContentTypeService */
    private $contentTypeService;

    /** @var PathService */
    private $pathService;

    /** @var FormFactory */
    private $formFactory;

    /**
     * @param ContentTypeService $contentTypeService
     * @param PathService $pathService
     * @param FormFactory $formFactory
     */
    public function __construct(
        ContentTypeService $contentTypeService,
        PathService $pathService,
        FormFactory $formFactory
    ) {
        $this->contentTypeService = $contentTypeService;
        $this->pathService = $pathService;
        $this->formFactory = $formFactory;
    }

    public function locationViewAction(ContentView $view)
    {
        $this->supplyPathLocations($view);
        $this->supplyContentType($view);
        $this->supplyContentActionForms($view);

        return $view;
    }

    /**
     * @param ContentView $view
     */
    private function supplyPathLocations(ContentView $view): void
    {
        $location = $view->getLocation();
        $pathLocations = $this->pathService->loadPathLocations($location);
        $view->addParameters(['pathLocations' => $pathLocations]);
    }

    /**
     * @param ContentView $view
     */
    private function supplyContentType(ContentView $view): void
    {
        $content = $view->getContent();
        $contentType = $this->contentTypeService->loadContentType($content->contentInfo->contentTypeId);
        $view->addParameters(['contentType' => $contentType]);
    }

    private function supplyContentActionForms(ContentView $view): void
    {
        $location = $view->getLocation();
        $locationViewUrl = $this->generateUrl($location);

        $locationCopyType = $this->formFactory->copyLocation(
            new LocationCopyData($location),
            null /* action handles the redirection */,
            $locationViewUrl
        );
        $locationMoveType = $this->formFactory->moveLocation(
            new LocationMoveData($location),
            null /* action handles the redirection */,
            $locationViewUrl
        );
        $locationTrashType = $this->formFactory->trashLocation(
            new LocationTrashData($location),
            null /* action handles the redirection */,
            $locationViewUrl
        );

        $view->addParameters([
            'form_location_copy' => $locationCopyType->createView(),
            'form_location_move' => $locationMoveType->createView(),
            'form_location_trash' => $locationTrashType->createView(),
        ]);
    }
}
