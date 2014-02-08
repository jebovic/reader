<?php

namespace Reader\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function simpleAction( Request $request )
    {
        $searchText = $request->request->get( 'searchText' );
        $storyType  = $this->get( 'fos_elastica.finder.reader.story' );
        $results    = $storyType->find( $searchText, 20 );

        return $this->render(
            'ReaderAdminBundle:Search:simple.html.twig',
            array(
                'stories'      => $results,
                'storiesCount' => count( $results ),
                'searchText'   => $searchText
            )
        );
    }
}
