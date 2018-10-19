<?php
/**
 * Created by PhpStorm.
 * User: hamdi
 * Date: 18/10/18
 * Time: 16:34
 */

namespace Application\Controller;


use Application\Form\PostForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\Post;

class PostController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Post manager.
     * @var Application\Service\PostManager
     */
    private $postManager;

    /**
     * Constructor is used for injecting dependencies into the controller.
     */
    public function __construct($entityManager, $postManager)
    {
        $this->entityManager = $entityManager;
        $this->postManager = $postManager;
    }

    /**
     * This action displays the "New Post" page. The page contains
     * a form allowing to enter post title, content and tags. When
     * the user clicks the Submit button, a new Post entity will
     * be created.
     */
    public function addAction()
    {
        // Create the form.
        $form = new PostForm();

        // Check whether this post is a POST request.
        if ($this->getRequest()->isPost()) {

            // Get POST data.
            $data = $this->params()->fromPost();

            // Fill form with data.
            $form->setData($data);
            if ($form->isValid()) {

                // Get validated form data.
                $data = $form->getData();

                // Use post manager service to add new post to database.
                $this->postManager->addNewPost($data);

                // Redirect the user to "index" page.
                return $this->redirect()->toRoute('posts');
            }
        }

        // Render the view template.
        return new ViewModel([
            'form' => $form
        ]);
    }

    // This is the default "index" action of the controller. It displays the
    // Posts page containing the recent blog posts.
    public function indexAction()
    {
        // Get recent posts
        $posts = $this->entityManager->getRepository(Post::class)
            ->findAll();

        // Render the view template
        return new ViewModel([
            'posts' => $posts
        ]);
    }

    // This action displays the page allowing to edit a post.
    public function editAction()
    {
        // Create the form.
        $form = new PostForm();

        // Get post ID.
        $postId = $this->params()->fromRoute('id', -1);

        // Find existing post in the database.
        $post = $this->entityManager->getRepository(Post::class)
            ->findOneById($postId);
        if ($post == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Check whether this post is a POST request.
        if ($this->getRequest()->isPost()) {

            // Get POST data.
            $data = $this->params()->fromPost();

            // Fill form with data.
            $form->setData($data);
            if ($form->isValid()) {

                // Get validated form data.
                $data = $form->getData();

                // Use post manager service to add new post to database.
                $this->postManager->updatePost($post, $data);

                // Redirect the user to "index" page.
                return $this->redirect()->toRoute('posts', ['action'=>'index']);
            }
        } else {
            $data = [
                'title' => $post->getTitle(),
                'content' => $post->getContent(),
                'status' => $post->getStatus()
            ];

            $form->setData($data);
        }

        // Render the view template.
        return new ViewModel([
            'form' => $form,
            'post' => $post
        ]);
    }

    public function deleteAction()
    {
        $postId = $this->params()->fromRoute('id', -1);

        $post = $this->entityManager->getRepository(Post::class)
            ->findOneById($postId);
        if ($post == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $this->postManager->removePost($post);

        // Redirect the user to "index" page.
        return $this->redirect()->toRoute('posts', ['action'=>'index']);
    }



    public function viewAction()
    {
        $postId = $this->params()->fromRoute('id', -1);

        $post = $this->entityManager->getRepository(Post::class)
            ->findOneById($postId);

        if ($post == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }


        // Check whether this post is a POST request.
        if($this->getRequest()->isPost()) {

            // Get POST data.
            $data = $this->params()->fromPost();


        }

        // Render the view template.
        return new ViewModel([
            'post' => $post,
            'postManager' => $this->postManager
        ]);
    }
}