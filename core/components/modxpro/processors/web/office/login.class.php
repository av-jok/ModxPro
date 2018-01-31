<?php

class OfficeLogin extends modProcessor
{

    function process()
    {
        define('MODX_ACTION_MODE', true);
        /** @var Office $Office */
        $Office = $this->modx->getService('office', 'Office', MODX_CORE_PATH . 'components/office/model/office/');

        $properties = [
            'username' => trim($this->getProperty('username')),
            'password' => trim($this->getProperty('password')),
        ];
        if (empty($properties['username'])) {
            return $this->failure($this->modx->lexicon('auth_err_username'));
        }
        if (empty($properties['password'])) {
            return $this->failure($this->modx->lexicon('auth_err_password'));
        }
        $properties['csrf'] = $Office->getCsrfToken();
        $response = json_decode($Office->loadAction('Auth/FormLogin', $properties), true);
        if (empty($response['success'])) {
            http_response_code(422);
        }
        $response['object'] = $response['data'];
        unset($response['data']);

        if (!empty($response['success'])) {
            $response['object']['callback'] = 'Auth.callbacks.login';
        }
        $response['object']['refresh'] = html_entity_decode($response['object']['refresh']);

        exit(json_encode($response));
    }

}

return 'OfficeLogin';