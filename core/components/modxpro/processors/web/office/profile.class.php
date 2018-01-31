<?php

class OfficeProfile extends modProcessor
{

    function process()
    {
        /** @var Office $Office */
        $Office = $this->modx->getService('office', 'Office', MODX_CORE_PATH . 'components/office/model/office/');
        $config = @$_SESSION['Office']['Profile'][$this->modx->context->key];
        if (empty($config)) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        $properties = array_merge($config, [
            'email' => $this->getProperty('email'),
            'fullname' => $this->getProperty('fullname'),
            'specifiedpassword' => $this->getProperty('specifiedpassword'),
            'confirmpassword' => $this->getProperty('confirmpassword'),
            'feedback' => (bool)$this->getProperty('feedback'),
            'work' => (bool)$this->getProperty('work'),
            'usename' => (bool)$this->getProperty('usename'),
            'city' => $this->modx->stripTags($this->getProperty('city')),
            'comment' => $this->modx->stripTags($this->getProperty('comment')),
            'photo' => $this->modx->stripTags($this->getProperty('photo')),
            'newphoto' => $this->modx->stripTags($this->getProperty('newphoto')),
        ]);
        $properties['csrf'] = $Office->getCsrfToken();

        // Username
        $username = preg_replace('#\s+#', '-', $this->getProperty('username'));
        if (is_numeric($username) || !preg_match('#^[a-z0-9_-]+$#i', $username)) {
            return $this->failure($this->modx->lexicon('auth_err_username_wrong'));
        }
        if ($this->modx->getCount('appUserName', ['username' => $username, 'user_id:!=' => $this->modx->user->id])) {
            return $this->failure($this->modx->lexicon('auth_err_username_exists'));
        }
        $properties['username'] = $username;

        // Website
        $website = trim($this->modx->stripTags($this->getProperty('website')));
        if ($parse = parse_url($website)) {
            $website = $parse['host'];
            if (!empty($parse['path']) && $parse['path'] != '/') {
                $website .= $parse['path'];
            }
        }
        $properties['website'] = (string)$website;

        // Phone
        $mobilephone = preg_replace('#[^\d]#', '', $this->getProperty('mobilephone'));
        $properties['mobilephone'] = substr($mobilephone, 0, 15);

        // Services
        $extended = $this->modx->user->Profile->get('extended');
        $services = [
            'github' => 'https://github.com/',
            'twitter' => 'https://twitter.com/',
            'vkontakte' => 'https://m.vk.com/',
            'telegram' => '',
            'skype' => '',
        ];
        foreach ($services as $service => $link) {
            $value = strip_tags(trim($this->getProperty($service)));
            $value = preg_replace('#[^\w-_\.]#', '', $value);

            if (!empty($value) && $value != @$extended[$service]) {
                if (!empty($link)) {
                    $ch = curl_init($link . $value);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_exec($ch);
                    if (curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200) {
                        $value = '';
                    }
                    curl_close($ch);
                }
            }
            $extended[$service] = $value;
        }
        $properties['extended'] = $extended;

        // Update
        $response = $Office->loadAction('Profile/Update', $properties);
        if (empty($response['success'])) {
            http_response_code(422);
            if (!empty($response['data'])) {
                if (!empty($response['data']['fullname'])) {
                    $response['message'] = $this->modx->lexicon('office_profile_err_field_fullname');
                } else {
                    $response['message'] = reset($response['data']);
                }
            }
            $response['data'] = [];
        } else {
            $data = [];
            foreach ($properties as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if (isset($response['data'][$k][$k2])) {
                            $data[$k2] = $response['data'][$k][$k2];
                        }
                    }
                } elseif (isset($response['data'][$k]) && $k != 'remote_key') {
                    $data[$k] = $response['data'][$k];
                }
            }
            $response['data'] = $data;
        }
        $response['object'] = $response['data'];
        unset($response['data']);

        if (!empty($response['success'])) {
            $response['object']['callback'] = 'Auth.callbacks.profile';
        }

        exit(json_encode($response));
    }

}

return 'OfficeProfile';