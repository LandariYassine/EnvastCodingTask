<?php

namespace App\Controller;

use App\Entity\TicketsReplies;
use App\Form\TicketsRepliesType;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;


/**
 * Tickets replies controller.
 * @Route("/api", name="api")
 */
class TicketsRepliesController extends AbstractFOSRestController
{
    /**
     * create ticket reply
     * @Rest\Post ("/ticket/reply")
     *
     * @return Response
     */
    public function postTicketReplyAction(Request $request)
    {
        try {
            $reply = new TicketsReplies();
            $form = $this->createForm(TicketsRepliesType::class, $reply);
            $data = json_decode($request->getContent(), true);
            $form->submit($data);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($reply);
                $em->flush();
                return $this->handleView($this->view(['success' => 'true', 'message' => 'Ticket reply created successfully'], Response::HTTP_CREATED));
            }
            return $this->handleView($this->view(['success' => 'false', 'message' => $form->getErrors()], Response::HTTP_BAD_REQUEST));
        } catch (\Exception $ex) {
            return $this->handleView($this->view(['success' => 'false', 'message' => $ex->getMessage()], Response::HTTP_BAD_REQUEST));
        }
    }

}
