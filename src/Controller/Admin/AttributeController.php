<?php

namespace App\Controller\Admin;

use App\Entity\Attribute;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/attributes", name="admin_attributes_")
 * @IsGranted("ROLE_USER")
 */

class AttributeController extends AbstractController
{
    /**
     * @Route("/{id}/remove", methods={"GET", "POST"}, name="remove",  requirements={"id"="\d+"})
     * @ParamConverter("attribute", class="App:Attribute")
     */
    public function remove(Attribute $attribute, Request $request) : Response
    {
        if (!$this->isCsrfTokenValid('remove', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_products_show', ['id' => $attribute->getProduct()->getId()]); //TODO: get route from request
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($attribute);
        $em->flush();

        $this->addFlash('success', 'attribute.deleted_successfully');

        return $this->redirectToRoute('admin_products_show', ['id' => $attribute->getProduct()->getId()]);
    }


}