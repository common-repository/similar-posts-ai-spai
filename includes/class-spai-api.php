<?php


class Spai_Api
{
    const API_DOMAIN = __SPAI_SITE__;
    const API_URL = self::API_DOMAIN . '/api-plugin-v1';

    private $token;

    private $timeout = 3;

    public function __construct( $token ) {
        $this->token = $token;

        $whitelist = array(
            '127.0.0.1',
            '172.21.0.1',
            '172.22.0.1',
            '127.0.0.1:8083',
            '127.0.0.1:8082',
        );
        if (
            (isset($_SERVER['REMOTE_ADDR']) && in_array($_SERVER['REMOTE_ADDR'], $whitelist))
            || (isset($_SERVER['HTTP_HOST']) && in_array($_SERVER['HTTP_HOST'], $whitelist))
        ) {
            $this->timeout = 10;
        }
    }

    public function checkToken()
    {
        $url = self::API_URL . '/auth/check-token';
        $response = wp_remote_get( $url, array(
                'headers'     => array(
                    'Authorization' => 'Bearer ' . $this->token,
                    'spai-plugin-ver' => SPAI_VERSION,
                ),
                'timeout'     => $this->timeout,
		        'sslverify' => false,
            )
        );
        return $response;
    }

    public function sendPostData( $data )
    {
        $url = self::API_URL . '/data/to-server';

        $response = wp_remote_post( $url, array(
                'headers'     => array(
                    'Authorization' => 'Bearer ' . $this->token,
                    'spai-plugin-ver' => SPAI_VERSION,
                ),
                'body' => $data,
		        'sslverify' => false,
                'timeout'     => $this->timeout,
            )
        );
        return $response;
    }

    public function getRelatedData( $post_id, $count )
    {
        $url = self::API_URL . '/related-posts/get';

        $data = array(
            'post_id' => $post_id,
            'post_count' => $count,
            'ip' => self::get_ip_address(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT']
        );

        $response = wp_remote_post( $url, array(
                'headers'     => array(
                    'Authorization' => 'Bearer ' . $this->token,
                    'spai-plugin-ver' => SPAI_VERSION,
                ),
                'body' => $data,
                'sslverify' => false,
                'timeout'     => $this->timeout,
            )
        );
        return $response;
    }

    /**
     * @param $data
     *
     * @return array|WP_Error
     */
    public function sendImpressionData( $data )
    {
        $url = self::API_URL . '/impression/save';

        $response = wp_remote_post( $url, array(
                'headers'     => array(
                    'Authorization' => 'Bearer ' . $this->token,
                    'spai-plugin-ver' => SPAI_VERSION,
                ),
                'body' => $data,
                'sslverify' => false,
                'timeout'     => $this->timeout,
            )
        );
        return $response;
    }

    /**
     * @param $data
     *
     * @return array|WP_Error
     */
    public function sendImpressionIsLoaded( $data )
    {
        $url = self::API_URL . '/impression/set-loaded';

        $response = wp_remote_post( $url, array(
                'headers'     => array(
                    'Authorization' => 'Bearer ' . $this->token,
                    'spai-plugin-ver' => SPAI_VERSION,
                ),
                'body' => $data,
                'sslverify' => false,
                'timeout'     => $this->timeout,
            )
        );
        return $response;
    }

    public function sendClickToRelatedPost( $data )
    {
        $url = self::API_URL . '/data/save-related-post-click';

        $response = wp_remote_post( $url, array(
                'headers'     => array(
                    'Authorization' => 'Bearer ' . $this->token,
                    'spai-plugin-ver' => SPAI_VERSION,
                ),
                'body' => $data,
		        'sslverify' => false,
                'timeout'     => $this->timeout,
            )
        );
        return $response['body'];
    }

	public function createConnectionToNewUser( $data )
	{
		$url = self::API_URL . '/auth/create-connection-to-new-user';

		$response = wp_remote_post( $url, array(
				'headers'     => array(
					'Authorization' => 'Bearer ' . $this->token,
                    'spai-plugin-ver' => SPAI_VERSION,
				),
				'body' => $data,
				'sslverify' => false,
				'timeout'     => $this->timeout,
			)
		);
		return $response;
	}

    /**
     * @param $data
     * @since 1.5.0
     */
    public function log( $data )
    {
        $url = self::API_URL . '/logger/save-log';

        $response = wp_remote_post( $url, array(
                'headers'     => array(
                    'Authorization' => 'Bearer ' . $this->token,
                    'spai-plugin-ver' => SPAI_VERSION,
                ),
                'body' => $data,
                'sslverify' => false,
                'timeout'     => $this->timeout * 5,
            )
        );
    }

    private static function get_ip_address() {
        if ( isset( $_SERVER['HTTP_X_REAL_IP'] ) ) {
            return sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_REAL_IP'] ) );
        } elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
            // Proxy servers can send through this header like this: X-Forwarded-For: client1, proxy1, proxy2
            // Make sure we always only send through the first IP in the list which should always be the client IP.
            return (string) rest_is_ip_address( trim( current( preg_split( '/,/', sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) ) ) ) );
        } elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
            return sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
        }
        return '';
    }
}
