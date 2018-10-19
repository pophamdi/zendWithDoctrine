<?php
/**
 * Created by PhpStorm.
 * User: hamdi
 * Date: 18/10/18
 * Time: 16:35
 */

namespace Application\Controller\Factory;


use Application\Controller\PostController;
use Application\Service\PostManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\IndexController;

class PostControllerFactory
{
    public function __invoke(ContainerInterface $container,
                             $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $postManager = $container->get(PostManager::class);

        // Instantiate the controller and inject dependencies
        return new PostController($entityManager, $postManager);
    }
}