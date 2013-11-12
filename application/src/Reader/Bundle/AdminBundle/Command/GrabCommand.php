<?php

namespace Reader\Bundle\AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Reader\Bundle\ReaderBundle\Document\Story;

class GrabCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('grab:stories')
            ->setDescription('Grab all stories')
            ->addArgument('site', InputArgument::REQUIRED, 'Site identifier')
            ->addArgument('start', InputArgument::OPTIONAL, 'Start on page #')
            ->addArgument('limit', InputArgument::OPTIONAL, 'Grab X pages')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $site           = ( $input->hasArgument('site') ) ? $input->getArgument('site') : null;
        $start          = (int)( $input->getArgument('start') ) ? $input->getArgument('start') : 1;
        $limit          = (int)( $input->getArgument('limit') ) ? $input->getArgument('limit') : 10;

        $output->writeln( sprintf( 'Grabbing stories for site "%s", from page #%s to #%s', $site, $start, $start+$limit-1) );

        $doctrine       = $this->getContainer()->get('doctrine_mongodb');
        $siteRepository = $doctrine->getRepository('ReaderBundle:Site');
        $site           = $siteRepository->findOneBy( array( 'identifier' => $site ) );

        if ( !is_null( $site ) )
        {
            $output->writeln( sprintf( 'Find site "%s" with identifier "%s"', $site->getTitle(), $site->getIdentifier() ) );
            $progress = $this->getHelperSet()->get('progress');
            $progress->start($output, $limit);
            $totalCount = 0;
            for ( $page = $start; $page < ($start + $limit); $page++ )
            {
                $grabber = $this->getContainer()->get('reader_grabber');
                $stories = $grabber
                    ->init($site, $page)
                    ->grab();

                if ( $stories && !empty($stories) )
                {
                    $count   = 0;
                    $now     = time();
                    $manager = $doctrine->getManager();
                    foreach( $stories as $position => $story )
                    {
                        if ( $story === false ) continue;
                        $storySum        = sha1( $story['title'] . '||' . $story['html'] );
                        $storyRepository = $doctrine->getRepository('ReaderBundle:Story');
                        $storyExists     = $storyRepository->findOneBy( array('textSum' => $storySum ) );

                        if ( is_null( $storyExists ) )
                        {
                            $storyDocument = new Story();
                            $storyDocument->setGrabbed( $now );
                            $storyDocument->setPage( $page );
                            $storyDocument->setSite( $site );
                            $storyDocument->setPosition( $position );
                            $storyDocument->setText( $story['html'] );
                            $storyDocument->setTitle( $story['title'] );
                            $storyDocument->setTextSum( $storySum );
                            $storyDocument->setRandomizer();

                            $imageUrl = $story['image'];
                            if ( !is_null( $imageUrl ) && $imageUrl != '' )
                            {
                                if ( strpos( $imageUrl, 'http' ) === 0)
                                {
                                    $storyDocument->setImage( $story['image'] );
                                }
                                else
                                {
                                    $storyDocument->setImage( $site->getUrl() . '/' . $story['image'] );
                                }
                            }

                            $manager->persist( $storyDocument );
                            $manager->flush();

                            $count++; $totalCount++;
                        }
                    }
                    $progress->advance();
                }
            }
            $progress->finish();
            $output->writeln( sprintf( 'Grabbing finished, %d stories grabbed on %d pages', $totalCount, $limit) );
        }
    }
}