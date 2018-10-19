<?php
namespace Application\Service;

use Application\Entity\Post;

use Zend\Filter\StaticFilter;

// The PostManager service is responsible for adding new posts.
class PostManager
{
    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    // Constructor is used to inject dependencies into the service.
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // This method adds a new post.
    public function addNewPost($data)
    {
        // Create new Post entity.
        $post = new Post();
        $post->setTitle($data['title']);
        $post->setContent($data['content']);
        $post->setStatus($data['status']);
        $currentDate = date('Y-m-d H:i:s');
        $post->setDateCreated($currentDate);

        // Add the entity to entity manager.
        $this->entityManager->persist($post);



        // Apply changes to database.
        $this->entityManager->flush();
    }

    // This method allows to update data of a single post.
    public function updatePost($post, $data)
    {
        $post->setTitle($data['title']);
        $post->setContent($data['content']);
        $post->setStatus($data['status']);



        // Apply changes to database.
        $this->entityManager->flush();
    }


    // Removes post and all associated comments.
    public function removePost($post)
    {
        $this->entityManager->remove($post);

        $this->entityManager->flush();
    }


}