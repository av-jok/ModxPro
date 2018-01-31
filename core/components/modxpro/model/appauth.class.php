<?php

class AppAuth implements
    OAuth2\Storage\AccessTokenInterface,
    OAuth2\Storage\ClientCredentialsInterface,
    OAuth2\Storage\AuthorizationCodeInterface
{
    /** @var modX $modx */
    var $modx;
    /** @var array $config */
    var $config = [];


    function __construct(modX $modx, array $config = [])
    {
        $this->modx = $modx;
        $this->config = $config;
    }


    /**
     * Look up the supplied oauth_token from storage.
     *
     * We need to retrieve access token data as we create and verify tokens.
     *
     * @param string $oauth_token - oauth_token to be check with.
     *
     * @return array|null - An associative array as below, and return NULL if the supplied oauth_token is invalid:
     *         'expires'   => $expires,   // Stored expiration in unix timestamp.
     *         'client_id' => $client_id, // (optional) Stored client identifier.
     *         'user_id'   => $user_id,   // (optional) Stored user identifier.
     *         'scope'     => $scope,     // (optional) Stored scope values in space-separated string.
     *         'id_token'  => $id_token   // (optional) Stored id_token (if "use_openid_connect" is true).
     */
    public function getAccessToken($oauth_token)
    {
        /** @var appAuthToken $record */
        if ($record = $this->modx->getObject('appAuthToken', ['token' => $oauth_token])) {
            return [
                'client_id' => $record->client_id,
                'user_id' => $record->user_id,
                'expires' => strtotime($record->expires),
                'scope' => $record->scope,
            ];
        }

        return null;
    }


    /**
     * Store the supplied access token values to storage.
     *
     * We need to store access token data as we create and verify tokens.
     *
     * @param string $oauth_token - oauth_token to be stored.
     * @param mixed $client_id - client identifier to be stored.
     * @param mixed $user_id - user identifier to be stored.
     * @param int $expires - expiration to be stored as a Unix timestamp.
     * @param string $scope - OPTIONAL Scopes to be stored in space-separated string.
     */
    public function setAccessToken($oauth_token, $client_id, $user_id, $expires, $scope = null)
    {
        /** @var appAuthToken $record */
        if (!$record = $this->modx->getObject('appAuthToken', ['token' => $oauth_token])) {
            $record = $this->modx->newObject('appAuthToken');
            $record->set('token', $oauth_token);
        }
        $record->fromArray([
            'client_id' => $client_id,
            'user_id' => $user_id,
            'expires' => date('Y-m-d H:i:s', $expires),
            'scope' => $scope,
        ]);

        $record->save();
    }


    /**
     * Fetch authorization code data (probably the most common grant type).
     *
     * Retrieve the stored data for the given authorization code.
     *
     * Required for OAuth2::GRANT_TYPE_AUTH_CODE.
     *
     * @param string $code Authorization code to be check with.
     *
     * @return array|bool An associative array as below, and NULL if the code is invalid
     *     "client_id"    => CLIENT_ID,      // REQUIRED Stored client identifier
     *     "user_id"      => USER_ID,        // REQUIRED Stored user identifier
     *     "expires"      => EXPIRES,        // REQUIRED Stored expiration in unix timestamp
     *     "scope"        => SCOPE,          // OPTIONAL Stored scope values in space-separated string
     */
    public function getAuthorizationCode($code)
    {
        /** @var appAuthCode $record */
        if ($record = $this->modx->getObject('appAuthCode', ['code' => $code])) {
            return [
                'client_id' => $record->client_id,
                'user_id' => $record->user_id,
                'expires' => strtotime($record->expires),
                'scope' => $record->scope,
            ];
        }

        return false;
    }


    /**
     * Take the provided authorization code values and store them somewhere.
     *
     * This function should be the storage counterpart to getAuthCode().
     *
     * If storage fails for some reason, we're not currently checking for
     * any sort of success/failure, so you should bail out of the script
     * and provide a descriptive fail message.
     *
     * Required for OAuth2::GRANT_TYPE_AUTH_CODE.
     *
     * @param string $code - Authorization code to be stored.
     * @param mixed $client_id - Client identifier to be stored.
     * @param mixed $user_id - User identifier to be stored.
     * @param string $redirect_uri - Redirect URI(s) to be stored in a space-separated string.
     * @param int $expires - Expiration to be stored as a Unix timestamp.
     * @param string $scope - OPTIONAL Scopes to be stored in space-separated string.
     */
    public function setAuthorizationCode($code, $client_id, $user_id, $redirect_uri, $expires, $scope = null)
    {
        /** @var appAuthCode $record */
        if (!$record = $this->modx->getObject('appAuthCode', ['code' => $code])) {
            $record = $this->modx->newObject('appAuthCode');
            $record->set('code', $code);
        }
        $record->fromArray([
            'client_id' => $client_id,
            'user_id' => $user_id,
            'expires' => date('Y-m-d H:i:s', $expires),
            'scope' => $scope,
        ]);

        $record->save();
    }


    /**
     * Once an Authorization Code is used, it must be expired
     *
     * @param $code
     */
    public function expireAuthorizationCode($code)
    {
        /** @var appAuthCode $record */
        if ($record = $this->modx->getObject('appAuthCode', ['code' => $code])) {
            $record->remove();
        }
    }


    /**
     * Make sure that the client credentials is valid.
     *
     * @param string $client_id Client identifier to be check with.
     * @param string $client_secret If a secret is required, check that they've given the right one.
     *
     * @return bool
     */
    public function checkClientCredentials($client_id, $client_secret = null)
    {
        return (bool)$this->modx->getCount('appAuthClient', ['id' => $client_id, 'secret' => $client_secret]);
    }


    /**
     * Determine if the client is a "public" client, and therefore
     * does not require passing credentials for certain grant types
     *
     * @param string $client_id Client identifier to be check with.
     *
     * @return bool
     */
    public function isPublicClient($client_id)
    {
        return true;
    }


    /**
     * Get client details corresponding client_id.
     *
     * OAuth says we should store request URIs for each registered client.
     * Implement this function to grab the stored URI for a given client id.
     *
     * @param $client_id
     * Client identifier to be check with.
     *
     * @return array|bool
     */
    public function getClientDetails($client_id)
    {
        /** @var AppAuthClient $client */
        if (!$client = $this->modx->getObject('appAuthClient', ['id' => $client_id])) {
            return false;
        }

        return [
            'title' => $client->title,
            'logo' => $client->logo,
            'description' => $client->description,
            'redirect_uri' => $client->redirect_uri,
            'client_id' => $client->id,
            'user_id' => $client->user_id,
            'grant_types' => $client->grant_types,
            'scope' => $client->scope,
        ];
    }


    /**
     * Get the scope associated with this client
     *
     * @param $client_id
     *
     * @return string the space-delineated scope list for the specified client_id
     */
    public function getClientScope($client_id)
    {
        $details = $this->getClientDetails($client_id);
        if ($details && !empty($details['scope'])) {
            return $details['scope'];
        }

        return '';
    }


    /**
     * Check restricted grant types of corresponding client identifier.
     *
     * @param string $client_id Client identifier to be check with.
     * @param string $grant_type Grant type to be check with
     *
     * @return bool
     */
    public function checkRestrictedGrantType($client_id, $grant_type)
    {
        $details = $this->getClientDetails($client_id);
        if (isset($details['grant_types'])) {
            $grant_types = explode(' ', $details['grant_types']);

            return in_array($grant_type, $grant_types);
        }

        return true;
    }
}