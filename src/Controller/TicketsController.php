<?php

namespace App\Controller;

use App\Entity\Tickets;
use App\Form\TicketsFormType;
use App\Repository\TicketsRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Tickets controller.
 * @Route("/api", name="api")
 */
class TicketsController extends AbstractFOSRestController

{
    public function __construct(TicketsRepository $ticketsRepository)
    {
        $this->ticketsRepository = $ticketsRepository;
    }

    /**
     * Create Ticket.
     * @Rest\Post("/ticket")
     *
     * @return Response
     */
    public function postTicketAction(Request $request)
    {
        try {
            $ticket = new Tickets();
            $form = $this->createForm(TicketsFormType::class, $ticket);
            $data = json_decode($request->getContent(), true);
            $form->submit($data);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($ticket);
                $em->flush();
                return $this->handleView($this->view(['success' => 'true', 'message' => 'Ticket created successfully'], Response::HTTP_CREATED));
            }
            return $this->handleView($this->view(['success' => 'false', 'message' => $form->getErrors()], Response::HTTP_BAD_REQUEST));
        } catch (\Exception $ex) {
            return $this->handleView($this->view(['success' => 'false', 'message' => $ex->getMessage()], Response::HTTP_BAD_REQUEST));
        }
    }

    /**
     * @Rest\Put("/ticket/{id}")
     * @Rest\View(populateDefaultVars=false)
     * @param Request $request
     * @return Response
     */
    public function putTicketAction(Request $request, int $id)
    {
        try {
            $ticket = $this->ticketsRepository->find($id);
            if ($ticket == null) {
                return $this->handleView($this->view(['success' => 'false', 'message' => 'Ticket with ID = ' . $id . ' not found'], Response::HTTP_BAD_REQUEST));
            }

            $form = $this->createForm(TicketsFormType::class, $ticket);
            $form->submit($request->request->all());
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($ticket);
                $em->flush();
                return $this->handleView($this->view(['success' => 'true', 'message' => 'Ticket updated successfully'], Response::HTTP_OK));

            }

            return $this->handleView($this->view(['success' => 'false', 'message' => $form->getErrors()], Response::HTTP_BAD_REQUEST));
        } catch (\Exception $ex) {
            return $this->handleView($this->view(['success' => 'false', 'message' => $ex->getMessage()], Response::HTTP_BAD_REQUEST));
        }
    }
}

