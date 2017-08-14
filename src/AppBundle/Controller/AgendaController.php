<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Agenda;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Agenda controller.
 *
 * @Route("agenda")
 */
class AgendaController extends Controller
{
    /**
     * Lists all agenda entities.
     *
     * @Route("/", name="agenda_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $usuarioActual = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $agendas = $em->getRepository('AppBundle:Agenda')->findBy(
          array('user_id' => $usuarioActual->getId())
        );

        return $this->render('agenda/index.html.twig', array(
            'agendas' => $agendas,
            'usuario' => $usuarioActual
        ));
    }

    /**
     * Creates a new agenda entity.
     *
     * @Route("/new", name="agenda_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $usuarioActual = $this->getUser();
        $agenda = new Agenda();
        $form = $this->createForm('AppBundle\Form\AgendaType', $agenda);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $agenda->setUserId($usuarioActual->getId());
            $em->persist($agenda);
            $em->flush();

            return $this->redirectToRoute('agenda_show', array('id' => $agenda->getId()));
        }

        return $this->render('agenda/new.html.twig', array(
            'agenda' => $agenda,
            'form' => $form->createView(),
            'usuario' => $usuarioActual
        ));
    }

    /**
     * Finds and displays a agenda entity.
     *
     * @Route("/{id}", name="agenda_show")
     * @Method("GET")
     */
    public function showAction(Agenda $agenda)
    {
        $usuarioActual = $this->getUser();
        $deleteForm = $this->createDeleteForm($agenda);

        return $this->render('agenda/show.html.twig', array(
            'agenda' => $agenda,
            'delete_form' => $deleteForm->createView(),
            'usuario' => $usuarioActual
        ));
    }

    /**
     * Displays a form to edit an existing agenda entity.
     *
     * @Route("/{id}/edit", name="agenda_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Agenda $agenda)
    {
        $usuarioActual = $this->getUser();
        $deleteForm = $this->createDeleteForm($agenda);
        $editForm = $this->createForm('AppBundle\Form\AgendaType', $agenda);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('agenda_edit', array('id' => $agenda->getId()));
        }

        return $this->render('agenda/edit.html.twig', array(
            'agenda' => $agenda,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'usuario' => $usuarioActual
        ));
    }

    /**
     * Deletes a agenda entity.
     *
     * @Route("/{id}", name="agenda_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Agenda $agenda)
    {
        $form = $this->createDeleteForm($agenda);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($agenda);
            $em->flush();
        }

        return $this->redirectToRoute('agenda_index');
    }

    /**
     * Creates a form to delete a agenda entity.
     *
     * @param Agenda $agenda The agenda entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Agenda $agenda)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('agenda_delete', array('id' => $agenda->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
