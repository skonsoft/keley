<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\View,
    FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View as FosView;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Handler\Exception\InvalidFormException;
use AppBundle\Entity\Group;

/**
 * 
 */
class GroupController extends FOSRestController {

    /**
     * @ApiDoc(
     *      resource=true,
     *      description="Get groups list",
     *      statusCodes={
     *          200="Returned when successful",
     *          500="Internal Server Error",
     *      },
     *      section="Group"
     * )
     * 
     * 
     * @View(serializerGroups={"list", "Default"})
     * 
     * 
     * @return array List of groups
     */
    public function getGroupsAction() {
        //here we should implement a paginated system (PaginatedRepresentation).
        // to do simple, i have just selected all groups
        return $this->getDoctrine()->getRepository('AppBundle:Group')->findAll();
    }

    /**
     * @ApiDoc(
     *      resource=true,
     *      description="Get Group Details",
     *      requirements={
     *          {"name"="id", "dataType"="integer", "requirement"="\d+", "required"=true, "description"="The group ID"}
     *      },
     *      statusCodes={
     *          200="Returned when successful",
     *          404="Resource not found"
     *      },
     *      section="Group"
     * )
     * 
     * 
     * @Get("/groups/{id}")
     * 
     * @View(serializerGroups={"Default", "details"})
     * 
     * @ParamConverter("group", class="AppBundle:Group")
     * 
     * @return array group Details
     */
    public function getGroupAction(Group $group) {
        return $group;
    }

    /**
     * Create a new group.<br/>
     * 
     * 
     * <strong>Sample:</strong>
     * <pre>
     * {
     *      "name": "Group de test"
     * }
     * </pre>
     * 
     * @ApiDoc(
     *      resource=true,
     *      description="Create a new group",
     *      statusCodes={
     *          201="Returned when successful"
     *      },
     *      section="Group"
     * )
     * 
     * 
     * @return array success message
     */
    public function postGroupAction(Request $request) {
        try {
            $group = $this->get('app.group_handler')
                    ->post();
            
            $routeOptions = array(
                'id' => $group->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('get_group', $routeOptions,
                                            Codes::HTTP_CREATED);

        } catch (InvalidFormException $e) {
            return FosView::create($this->get('app.form_utils')->getFormErrors($e->getForm()), Codes::HTTP_BAD_REQUEST);
        }
    }

}
