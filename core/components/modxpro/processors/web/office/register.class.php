<?php

class OfficeRegister extends modProcessor
{

    function process()
    {
        define('MODX_ACTION_MODE', true);
        /** @var Office $Office */
        $Office = $this->modx->getService('office', 'Office', MODX_CORE_PATH . 'components/office/model/office/');

        $username = preg_replace('#\s+#', '-', $this->getProperty('username'));
        if (empty($username)) {
            return $this->failure($this->modx->lexicon('auth_err_username'));
        }
        if (is_numeric($username) || !preg_match('#^[a-z0-9_-]$#i', $username)) {
            return $this->failure($this->modx->lexicon('auth_err_username_wrong'));
        }
        if ($this->modx->getCount('appUserName', ['username' => $username, 'user_id:!=' => $this->modx->user->id])) {
            return $this->failure($this->modx->lexicon('auth_err_username_exists'));
        }

        $properties = $this->getProperties();
        $properties['username'] = $username;
        $properties['csrf'] = $Office->getCsrfToken();
        $response = json_decode($Office->loadAction('Auth/formRegister', $properties), true);
        if (empty($response['success'])) {
            http_response_code(422);
        }
        $response['object'] = $response['data'];
        unset($response['data']);

        if (!empty($response['success'])) {
            $response['object']['callback'] = 'Auth.callbacks.register';
        }

        exit(json_encode($response));
    }

}

return 'OfficeRegister';