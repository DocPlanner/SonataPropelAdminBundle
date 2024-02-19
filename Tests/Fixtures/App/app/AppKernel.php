<?php

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * @codeCoverageIgnore
 */
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        return array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),

            new Propel\Bundle\PropelBundle\PropelBundle(),

            new \Sonata\Twig\Bridge\Symfony\SonataTwigBundle(),
            new \Sonata\Doctrine\Bridge\Symfony\SonataDoctrineBundle(),
            new \Sonata\Form\Bridge\Symfony\SonataFormBundle(),
            new \Sonata\Exporter\Bridge\Symfony\SonataExporterBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Sonata\AdminBundle\SonataAdminBundle(),
            new Sonata\PropelAdminBundle\SonataPropelAdminBundle(),
        );
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config.yml');
    }
}
