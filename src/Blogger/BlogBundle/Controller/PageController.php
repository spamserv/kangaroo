<?php
// src/Blogger/BlogBundle/Controller/PageController.php

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use Blogger\BlogBundle\Entity\Inquiry;
use Blogger\BlogBundle\Form\InquiryType;
use Blogger\BlogBundle\Entity\Blog;
use Blogger\BlogBundle\Entity\Comment;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\JsonResponse;


class PageController extends Controller
{

	/**
	* @Route("/", name="homepage")
	* @Method("GET")
	*/
    public function indexAction()
    {	
        $em = $this->getDoctrine()
                   ->getEntityManager();

        $blogs = $em->getRepository('BloggerBlogBundle:Blog')
                    ->getLatestBlogs();

        return $this->render('BloggerBlogBundle:Page:index.html.twig', array(
            'blogs' => $blogs
        ));
    }

    /**
	* @Route("/about", name="about")
	* @Method("GET")
	*/
    public function aboutAction()
    {
        return $this->render('BloggerBlogBundle:Page:about.html.twig');
    }

    /**
	* @Route("/contact", name="contact")
	* @Method({"GET","POST"})
	*/
    public function contactAction(Request $request)
    {
     	$inquiry = new Inquiry();
	    $form = $this->createForm(InquiryType::class, $inquiry);

	    if ($request->getMethod() == 'POST') {
	        //$form->bindRequest($request);

		    if ($form->isValid()) {
		    	var_dump("MESSAGE");
		        $message = \Swift_Message::newInstance()
		            ->setSubject('Contact enquiry from symblog')
		            ->setFrom('enquiries@symblog.co.uk')
		            ->setTo('email@email.com')
		            ->setBody($this->renderView('BloggerBlogBundle:Page:contactEmail.txt.twig', array('enquiry' => $enquiry)));
		        $this->get('mailer')->send($message);

	         	$this->get('session')->getFlashBag()->add('blogger-notice', 'Your contact enquiry was successfully sent. Thank you!');

		        // Redirect - This is important to prevent users re-posting
		        // the form if they refresh the page
		        return $this->redirect($this->generateUrl('contact'));
		    }
	    }

	    return $this->render('BloggerBlogBundle:Page:contact.html.twig', array(
	        'form' => $form->createView()
	    ));
 	}

    public function sidebarAction()
    {
        $em = $this->getDoctrine()
                   ->getEntityManager();

        $tags = $em->getRepository('BloggerBlogBundle:Blog')
                   ->getTags();

        $commentLimit = $this->container
                            ->getParameter('blogger_blog.comments.latest_comment_limit');

        $latestComments = $em->getRepository('BloggerBlogBundle:Comment')
                            ->getLatestComments($commentLimit);

        $tagWeights = $em->getRepository('BloggerBlogBundle:Blog')
                         ->getTagWeights($tags);

        return $this->render('BloggerBlogBundle:Page:sidebar.html.twig', array(
            'latestComments' => $latestComments,
            'tags' => $tagWeights
        ));
    }

    /**
     * @Route("/load/blogs", name="load_blogs")
     * @Method("GET")
     */
    public function loadAction()
    {
        $em = $this->get('doctrine')->getManager();
        $response = new JsonResponse();

        $blog1 = $em->getRepository('BloggerBlogBundle:Blog')->find(1);
        $blog2 = $em->getRepository('BloggerBlogBundle:Blog')->find(2);
        $blog3 = $em->getRepository('BloggerBlogBundle:Blog')->find(3);
        $blog4 = $em->getRepository('BloggerBlogBundle:Blog')->find(4);
        $blog5 = $em->getRepository('BloggerBlogBundle:Blog')->find(5);

        $comment = new Comment();
        $comment->setUser('symfony');
        $comment->setComment('To make a long story short. You can\'t go wrong by choosing Symfony! And no one has ever been fired for using Symfony.');
        $comment->setBlog($blog1);
        $em->persist($comment);

        $comment = new Comment();
        $comment->setUser('David');
        $comment->setComment('To make a long story short. Choosing a framework must not be taken lightly; it is a long-term commitment. Make sure that you make the right selection!');
        $comment->setBlog($blog1);
        $em->persist($comment);

        $comment = new Comment();
        $comment->setUser('Dade');
        $comment->setComment('Anything else, mom? You want me to mow the lawn? Oops! I forgot, New York, No grass.');
        $comment->setBlog($blog2);
        $em->persist($comment);

        $comment = new Comment();
        $comment->setUser('Kate');
        $comment->setComment('Are you challenging me? ');
        $comment->setBlog($blog2);
        $comment->setCreated(new \DateTime("2011-07-23 06:15:20"));
        $em->persist($comment);

        $comment = new Comment();
        $comment->setUser('Dade');
        $comment->setComment('Name your stakes.');
        $comment->setBlog($blog2);
        $comment->setCreated(new \DateTime("2011-07-23 06:18:35"));
        $em->persist($comment);

        $comment = new Comment();
        $comment->setUser('Kate');
        $comment->setComment('If I win, you become my slave.');
        $comment->setBlog($blog2);
        $comment->setCreated(new \DateTime("2011-07-23 06:22:53"));
        $em->persist($comment);

        $comment = new Comment();
        $comment->setUser('Dade');
        $comment->setComment('Your SLAVE?');
        $comment->setBlog($blog2);
        $comment->setCreated(new \DateTime("2011-07-23 06:25:15"));
        $em->persist($comment);

        $comment = new Comment();
        $comment->setUser('Kate');
        $comment->setComment('You wish! You\'ll do shitwork, scan, crack copyrights...');
        $comment->setBlog($blog2);
        $comment->setCreated(new \DateTime("2011-07-23 06:46:08"));
        $em->persist($comment);

        $comment = new Comment();
        $comment->setUser('Dade');
        $comment->setComment('And if I win?');
        $comment->setBlog($blog2);
        $comment->setCreated(new \DateTime("2011-07-23 10:22:46"));
        $em->persist($comment);

        $comment = new Comment();
        $comment->setUser('Kate');
        $comment->setComment('Make it my first-born!');
        $comment->setBlog($blog2);
        $comment->setCreated(new \DateTime("2011-07-23 11:08:08"));
        $em->persist($comment);

        $comment = new Comment();
        $comment->setUser('Dade');
        $comment->setComment('Make it our first-date!');
        $comment->setBlog($blog2);
        $comment->setCreated(new \DateTime("2011-07-24 18:56:01"));
        $em->persist($comment);

        $comment = new Comment();
        $comment->setUser('Kate');
        $comment->setComment('I don\'t DO dates. But I don\'t lose either, so you\'re on!');
        $comment->setBlog($blog2);
        $comment->setCreated(new \DateTime("2011-07-25 22:28:42"));
        $em->persist($comment);

        $comment = new Comment();
        $comment->setUser('Stanley');
        $comment->setComment('It\'s not gonna end like this.');
        $comment->setBlog($blog3);
        $em->persist($comment);

        $comment = new Comment();
        $comment->setUser('Gabriel');
        $comment->setComment('Oh, come on, Stan. Not everything ends the way you think it should. Besides, audiences love happy endings.');
        $comment->setBlog($blog3);
        $em->persist($comment);

        $comment = new Comment();
        $comment->setUser('Mile');
        $comment->setComment('Doesn\'t Bill Gates have something like that?');
        $comment->setBlog($blog5);
        $em->persist($comment);

        $comment = new Comment();
        $comment->setUser('Gary');
        $comment->setComment('Bill Who?');
        $comment->setBlog($blog5);
        $em->persist($comment);

        $em->flush();

        return $response;
    }
}