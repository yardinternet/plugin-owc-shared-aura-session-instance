<?php

/**
 * Plugin Name: Yard | Shared Aura Session Instance
 * Plugin URI: https://www.yard.nl
 * Description: Provides a shared Aura Session instance via a filter hook, enabling other plugins to utilize this instance for managing sessions securely.
 * Author: Yard | Digital Agency
 * Author URI: https://www.yard.nl
 * Version: 1.0.0
 * License: GPL3.0
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 */
if (! defined('WPINC')) {
    exit;
}

function create_session_instance()
{
    if (! class_exists('\Aura\Session\SessionFactory')) {
        return null;
    }

    try {
        $session_factory = new \Aura\Session\SessionFactory;
        $session = $session_factory->newInstance($_COOKIE);
        $session->setCookieParams([
            'secure'   => true,
            'httponly' => true,
        ]);
    } catch(\Exception $e) {
        $session = null;
    }

    return $session;
}

$session_instance = create_session_instance();

/**
 * Filter hook to provide a shared Aura Session instance.
 * Other plugins can utilize this instance instead of creating their own.
 */
add_filter('yard_aura_session_instance', function () use ($session_instance) {
    return $session_instance;
});
