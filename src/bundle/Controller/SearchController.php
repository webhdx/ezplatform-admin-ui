<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUiBundle\Controller;

use eZ\Publish\Core\Repository\SearchService;
use EzSystems\EzPlatformAdminUi\Form\Data\Search\SearchData;
use EzSystems\EzPlatformAdminUi\Tab\Dashboard\PagerContentToDataMapper;
use Pagerfanta\View\TwitterBootstrap4View;
use Symfony\Component\HttpFoundation\Request;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\API\Repository\Values\Content\Query\SortClause;
use eZ\Publish\API\Repository\Values\Content\Query;
use Pagerfanta\Pagerfanta;
use eZ\Publish\Core\Pagination\Pagerfanta\ContentSearchAdapter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use EzSystems\EzPlatformAdminUi\Form\Factory\FormFactory;
use EzSystems\EzPlatformAdminUi\Form\SubmitHandler;

class SearchController extends Controller
{
    /** @var SearchService */
    private $searchService;

    /** @var PagerContentToDataMapper */
    private $pagerContentToDataMapper;

    /** @var Router */
    private $router;

    /** @var FormFactory */
    private $formFactory;

    /** @var SubmitHandler */
    private $submitHandler;

    /**
     * @param SearchService $searchService
     * @param PagerContentToDataMapper $pagerContentToDataMapper
     * @param RouterInterface $router
     * @param FormFactory $formFactory
     * @param SubmitHandler $submitHandler
     */
    public function __construct(
        SearchService $searchService,
        PagerContentToDataMapper $pagerContentToDataMapper,
        RouterInterface $router,
        FormFactory $formFactory,
        SubmitHandler $submitHandler
    ) {
        $this->searchService = $searchService;
        $this->pagerContentToDataMapper = $pagerContentToDataMapper;
        $this->router = $router;
        $this->formFactory = $formFactory;
        $this->submitHandler = $submitHandler;
    }

    /**
     * Renders the simple search form and search results.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function searchAction(Request $request): Response
    {
        $search = $request->query->get('search');
        $limit = $search['limit'] ?? 10;
        $page = $search['page'] ?? 1;
        $query = $search['query'];

        $form = $this->formFactory->createSearchForm(new SearchData($limit, $page, $query), 'search', [
            'method' => 'GET',
            'csrf_protection' => false,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $result = $this->submitHandler->handle($form, function (SearchData $data) use ($form) {
                $limit = $data->getLimit();
                $page = $data->getPage();
                $queryString = $data->getQuery();

                $query = new Query();
                $query->filter = new Criterion\LogicalAnd(
                    [
                        new Criterion\Visibility(Criterion\Visibility::VISIBLE),
                        new Criterion\FullText($queryString),
                    ]
                );

                $query->sortClauses[] = new SortClause\DateModified(Query::SORT_ASC);

                $pagerfanta = new Pagerfanta(
                    new ContentSearchAdapter(
                        $query,
                        $this->searchService
                    )
                );

                $pagerfanta->setMaxPerPage($limit);
                $pagerfanta->setCurrentPage($page);

                $urlGenerator = $this->router;

                $routeGenerator = function ($page) use (&$urlGenerator, $data) {
                    return $urlGenerator->generate('ezplatform.search', [
                        'search' => [
                            'query' => $data->getQuery(),
                            'page' => $page,
                            'limit' => $data->getLimit(),
                        ],
                    ]);
                };

                $pagination = (new TwitterBootstrap4View())->render($pagerfanta, $routeGenerator);

                return $this->render('@EzPlatformAdminUi/admin/search/list.html.twig', [
                    'results' => $this->pagerContentToDataMapper->map($pagerfanta),
                    'form' => $form->createView(),
                    'pagination' => $pagination,
                    'pagerfanta' => $pagerfanta,
                ]);
            });

            if ($result instanceof Response) {
                return $result;
            }
        }

        return $this->render('@EzPlatformAdminUi/admin/search/search.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
