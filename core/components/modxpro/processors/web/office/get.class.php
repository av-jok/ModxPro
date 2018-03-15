<?php

class OfficeGetFormLogin extends modProcessor
{
    public $tpl = '@FILE chunks/office/form-login.tpl';
    const providerTpl = '@FILE chunks/office/profile/provider.tpl';
    const activeProviderTpl = '@FILE chunks/office/profile/provider-active.tpl';


    /**
     * @return bool|null|string
     */
    public function initialize()
    {
        if ($this->modx->user->isAuthenticated($this->modx->context->key)) {
            $this->modx->lexicon->load('office:auth');

            return $this->modx->lexicon('office_auth_err_already_logged');
        }

        return parent::initialize();
    }


    /**
     * @return array|string
     */
    public function process()
    {
        /** @var App $App */
        $App = $this->modx->getService('App');
        $providers = '';
        if (file_exists(MODX_CORE_PATH . 'components/hybridauth/')) {
            $load = $this->modx->loadClass('hybridauth', MODX_CORE_PATH . 'components/hybridauth/model/hybridauth/',
                false, true);
            if ($load) {
                $HybridAuth = new HybridAuth($this->modx);
                $HybridAuth->initialize($this->modx->context->key);
                $providers = $HybridAuth->getProvidersLinks(
                    $this::providerTpl,
                    $this::activeProviderTpl
                );
            }
        }

        return $this->success('', [
            'html' => $App->pdoTools->getChunk($this->tpl, [
                'providers' => $providers,
            ]),
        ]);
    }
}

return 'OfficeGetFormLogin';