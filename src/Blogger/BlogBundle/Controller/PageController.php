<?php
// src/Blogger/BlogBundle/Controller/PageController.php

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use Blogger\BlogBundle\Entity\Inquiry;
use Blogger\BlogBundle\Form\InquiryType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class PageController extends Controller
{

	/**
	* @Route("/", name="homepage")
	* @Method("GET")
	*/
    public function indexAction()
    {
        return $this->render('BloggerBlogBundle:Page:index.html.twig');
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
}