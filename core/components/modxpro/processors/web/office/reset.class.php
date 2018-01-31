<?php

class OfficeReset extends modProcessor
{

    function process()
    {
        define('MODX_ACTION_MODE', true);
        /** @var Office $Office */
        $Office = $this->modx->getService('office', 'Office', MODX_CORE_PATH . 'components/office/model/office/');

        $properties = [
            'email' => trim($this->getProperty('email')),
        ];
        if (empty($properties['email'])) {
            return $this->failure($this->modx->lexicon('auth_err_email'));
        }
        $properties['csrf'] = $Office->getCsrfToken();
        $response = json_decode($Office->loadAction('Auth/FormLogin', $properties), true);
        if (empty($response['success'])) {
            http_response_code(422);
        }
        $response['object'] = $response['data'];
        unset($response['data']);

        if (!empty($response['success'])) {
            $response['object']['callback'] = 'Auth.callbacks.reset';
        }

        exit(json_encode($response));
    }

}

return 'OfficeReset';