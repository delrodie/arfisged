<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Service;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Service controller.
 *
 * @Route("service")
 */
class ServiceController extends Controller
{
    /**
     * Lists all service entities.
     *
     * @Route("/", name="service_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();

        // test de droit d'accès
        $acces = $this->get('autorisation')->droitacces($user); // Service
        if ($acces) {
          return $acces;
        }

        // Enregistrement
        $service = new Service();
        $form = $this->createForm('AppBundle\Form\ServiceType', $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($service);
            $em->flush();

            $this->addFlash('notice', "Le service ".$service->getNom()." a été crée avec succès.!");

            return $this->redirectToRoute('service_index');
        }

        $services = $em->getRepository('AppBundle:Service')->findAll();

        return $this->render('service/index.html.twig', array(
            'services' => $services,
            'service' => $service,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new service entity.
     *
     * @Route("/new", name="service_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $service = new Service();
        $form = $this->createForm('AppBundle\Form\ServiceType', $service);
        $form->handleRequest($request);

        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($service);
            $em->flush();

            return $this->redirectToRoute('service_show', array('id' => $service->getId()));
        }

        return $this->render('service/new.html.twig', array(
            'service' => $service,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a service entity.
     *
     * @Route("/{id}", name="service_show")
     * @Method("GET")
     */
    public function showAction(Service $service)
    {
        $deleteForm = $this->createDeleteForm($service);

        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        return $this->render('service/show.html.twig', array(
            'service' => $service,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing service entity.
     *
     * @Route("/{slug}/edit", name="service_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Service $service)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();

        // test de droit d'accès
        $acces = $this->get('autorisation')->droitacces($user); // Service
        if ($acces) {
          return $acces;
        }

        $deleteForm = $this->createDeleteForm($service);
        $editForm = $this->createForm('AppBundle\Form\ServiceType', $service);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('notice', "Le service ".$service->getNom()." a été modifié avec succès.!");

            return $this->redirectToRoute('service_index');
        }

        $services = $em->getRepository('AppBundle:Service')->findAll();

        return $this->render('service/edit.html.twig', array(
            'service' => $service,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'services' => $services,
        ));
    }

    /**
     * Deletes a service entity.
     *
     * @Route("/{id}", name="service_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Service $service)
    {
        $form = $this->createDeleteForm($service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($service);
            $em->flush();

            $this->addFlash('notice', "Le service ".$service->getNom()." a été supprimé avec succès.!");
        }

        return $this->redirectToRoute('service_index');
    }

    /**
     * Creates a form to delete a service entity.
     *
     * @param Service $service The service entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Service $service)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('service_delete', array('id' => $service->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
