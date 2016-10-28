<?php
// src/Blogger/BlogBundle/Controller/PageController.php

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use Blogger\BlogBundle\Entity\Inquiry;
use Blogger\BlogBundle\Form\InquiryType;
use Blogger\BlogBundle\Entity\Blog;

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

        $blogs = $em->createQueryBuilder()
                    ->select('b')
                    ->from('BloggerBlogBundle:Blog',  'b')
                    ->addOrderBy('b.created', 'DESC')
                    ->getQuery()
                    ->getResult();

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
		        return $this->redirect($this->generateUrl('BloggerBlogBundle_contact'));
		    }
	    }

	    return $this->render('BloggerBlogBundle:Page:contact.html.twig', array(
	        'form' => $form->createView()
	    ));
 	}

 	/**
	* @Route("/blog/{id}", name="blog_show", requirements={"id": "\d+"})
	* @Method("GET")
	*/
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $blog = $em->getRepository('BloggerBlogBundle:Blog')->find($id);

        if (!$blog) {
            throw $this->createNotFoundException('Unable to find Blog post.');
        }

        return $this->render('BloggerBlogBundle:Blog:show.html.twig', array(
            'blog'	=>	$blog,
        ));
    }

    /**
     * @Route("/load/blogs", name="load_blogs")
     * @Method("GET")
     */
    public function loadAction()
    {
        $em = $this->get('doctrine')->getEntityManager();
        $response = new JsonResponse();

        $blog1 = new Blog();
        $blog1->setTitle('A day with Symfony2');
        $blog1->setBlog('Lorem ipsum dolor sit amet, consectetur adipiscing eletra electrify denim vel ports.\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ut velocity magna. Etiam vehicula nunc non leo hendrerit commodo. Vestibulum vulputate mauris eget erat congue dapibus imperdiet justo scelerisque. Nulla consectetur tempus nisl vitae viverra. Cras el mauris eget erat congue dapibus imperdiet justo scelerisque. Nulla consectetur tempus nisl vitae viverra. Cras elementum molestie vestibulum. Morbi id quam nisl. Praesent hendrerit, orci sed elementum lobortis, justo mauris lacinia libero, non facilisis purus ipsum non mi. Aliquam sollicitudin, augue id vestibulum iaculis, sem lectus convallis nunc, vel scelerisque lorem tortor ac nunc. Donec pharetra eleifend enim vel porta.');
        $blog1->setImage('beach.jpg');
        $blog1->setAuthor('dsyph3r');
        $blog1->setTags('symfony2, php, paradise, symblog');
        $blog1->setCreated(new \DateTime());
        $blog1->setUpdated($blog1->getCreated());
        $em->persist($blog1);

        $blog2 = new Blog();
        $blog2->setTitle('The pool on the roof must have a leak');
        $blog2->setBlog('Vestibulum vulputate mauris eget erat congue dapibus imperdiet justo scelerisque. Na. Cras elementum molestie vestibulum. Morbi id quam nisl. Praesent hendrerit, orci sed elementum lobortis.');
        $blog2->setImage('pool_leak.jpg');
        $blog2->setAuthor('Zero Cool');
        $blog2->setTags('pool, leaky, hacked, movie, hacking, symblog');
        $blog2->setCreated(new \DateTime("2011-07-23 06:12:33"));
        $blog2->setUpdated($blog2->getCreated());
        $em->persist($blog2);

        $blog3 = new Blog();
        $blog3->setTitle('Misdirection. What the eyes see and the ears hear, the mind believes');
        $blog3->setBlog('Lorem ipsumvehicula nunc non leo hendrerit commodo. Vestibulum vulputate mauris eget erat congue dapibus imperdiet justo scelerisque.');
        $blog3->setImage('misdirection.jpg');
        $blog3->setAuthor('Gabriel');
        $blog3->setTags('misdirection, magic, movie, hacking, symblog');
        $blog3->setCreated(new \DateTime("2011-07-16 16:14:06"));
        $blog3->setUpdated($blog3->getCreated());
        $em->persist($blog3);

        $blog4 = new Blog();
        $blog4->setTitle('The grid - A digital frontier');
        $blog4->setBlog('Lorem commodo. Vestibulum vulputate mauris eget erat congue dapibus imperdiet justo scelerisque. Nulla consectetur tempus nisl vitae viverra.');
        $blog4->setImage('the_grid.jpg');
        $blog4->setAuthor('Kevin Flynn');
        $blog4->setTags('grid, daftpunk, movie, symblog');
        $blog4->setCreated(new \DateTime("2011-06-02 18:54:12"));
        $blog4->setUpdated($blog4->getCreated());
        $em->persist($blog4);

        $blog5 = new Blog();
        $blog5->setTitle('You\'re either a one or a zero. Alive or dead');
        $blog5->setBlog('Lorem ipsum dolor sit amet, consectetur adipiscing elittibulum vulputate mauris eget erat congue dapibus imperdiet justo scelerisque.');
        $blog5->setImage('one_or_zero.jpg');
        $blog5->setAuthor('Gary Winston');
        $blog5->setTags('binary, one, zero, alive, dead, !trusting, movie, symblog');
        $blog5->setCreated(new \DateTime("2011-04-25 15:34:18"));
        $blog5->setUpdated($blog5->getCreated());
        $em->persist($blog5);

        $em->flush();

        return $response;
    }
}