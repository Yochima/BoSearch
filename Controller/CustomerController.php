<?php

namespace BoSearch\Controller;

use BoSearch\BoSearch;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Form\Exception\FormValidationException;

/**
 * Class CustomerController
 * @package BoSearch\Controller
 * @author Etienne Perriere <eperriere@openstudio.fr>
 */
class CustomerController extends BaseAdminController
{
    public function searchAction()
    {
        if (null !== $response = $this->checkAuth([AdminResources::MODULE], ["bosearch"], AccessManager::VIEW)) {
            return $response;
        }

        $baseForm = $this->createForm("customer-search-form");
        $error_message = false;

        try {
            $form = $this->validateForm($baseForm);

            // Set parsed data in the request to keep Datetime format for dates
            $this->getRequest()->request->set(BoSearch::PARSED_DATA, $form->getData());

        } catch (FormValidationException $ex) {
            $error_message = $this->createStandardFormValidationErrorMessage($ex);
        } catch (\Exception $ex) {
            $error_message = $ex->getMessage();
        }

        if (false !== $error_message) {
            $this->setupFormErrorContext(
                $this->getTranslator()->trans("Searching customer"),
                $error_message,
                $baseForm,
                null
            );
        }

        return $this->render('customers');
    }
}