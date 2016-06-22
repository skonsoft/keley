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
use AppBundle\Entity\User;

/**
 * 
 */
class UsersController extends FOSRestController {

    /**
     * @ApiDoc(
     *      resource=true,
     *      description="Get Users list",
     *      statusCodes={
     *          200="Returned when successful",
     *          500="Internal Server Error",
     *      },
     *      section="User"
     * )
     * 
     * 
     * @View(serializerGroups={"list", "Default"})
     * 
     * 
     * @return array List of users
     */
    public function getUsersAction() {
        return $this->getDoctrine()->getRepository('AppBundle:User')->findBy(array('enabled'=>true));
    }

    /**
     * @ApiDoc(
     *      resource=true,
     *      description="Get User Details",
     *      requirements={
     *          {"name"="id", "dataType"="integer", "requirement"="\d+", "required"=true, "description"="The user ID"}
     *      },
     *      statusCodes={
     *          200="Returned when successful",
     *          404="Resource not found"
     *      },
     *      section="User"
     * )
     * 
     * 
     * @Get("/users/{id}")
     * 
     * @View(serializerGroups={"Default", "details"})
     * 
     * @ParamConverter("user", class="AppBundle:User")
     * 
     * @return array user Details
     */
    public function getUserAction(User $user) {
        return $user;
    }

    /**
     * Create a new user.<br/>
     * 
     * 
     * <strong>Sample:</strong>
     * <pre>
     * {
     *      "email": "test@test.com",
     *      "firstname": "test",
     *      "lastname": "testina"
     * }
     * </pre>
     * 
     * @ApiDoc(
     *      resource=true,
     *      description="Create a new user",
     *      statusCodes={
     *          201="Returned when successful"
     *      },
     *      section="User"
     * )
     * 
     * 
     * @return array success message
     */
    public function postUserAction(Request $request) {
        try {
            $user = $this->get('app.user_handler')
                    ->post();
            
            $routeOptions = array(
                'id' => $user->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('get_user', $routeOptions,
                                            Codes::HTTP_CREATED);

        } catch (InvalidFormException $e) {
            return FosView::create($this->get('app.form_utils')->getFormErrors($e->getForm()), Codes::HTTP_BAD_REQUEST);
        }
    }

}
