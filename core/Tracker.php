<?php
/**
 * SwiftTrace - Analytics Tracker Class
 * Captura y procesa los metadatos del visitante.
 */

class Tracker {
    
    /**
     * Recopila todos los datos del visitante en un array.
     * @return array
     */
    public static function getUserData(): array {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        
        return [
            'ip'         => self::getIP(),
            'user_agent' => $userAgent,
            'referer'    => $_SERVER['HTTP_REFERER'] ?? 'Direct',
            'os'         => self::getOS($userAgent),
            'browser'    => self::getBrowser($userAgent)
        ];
    }

    /**
     * Obtiene la IP real, manejando proxies y Cloudflare si es necesario.
     */
    private static function getIP(): string {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        }
    }

    /**
     * Extrae el Sistema Operativo usando expresiones regulares.
     */
    private static function getOS(string $userAgent): string {
        $osPlatform = "Unknown OS";
        $osArray = [
            '/windows nt 10/i'      =>  'Windows 10/11',
            '/windows nt 6.3/i'     =>  'Windows 8.1',
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/macintosh|mac os x/i' =>  'Mac OS X',
            '/android/i'            =>  'Android',
            '/iphone/i'             =>  'iOS',
            '/ipad/i'               =>  'iPadOS',
            '/linux/i'              =>  'Linux'
        ];

        foreach ($osArray as $regex => $value) {
            if (preg_match($regex, $userAgent)) {
                $osPlatform = $value;
                break; // Detenemos el bucle al encontrar coincidencia
            }
        }
        return $osPlatform;
    }

    /**
     * Extrae el Navegador Web usando expresiones regulares.
     */
    private static function getBrowser(string $userAgent): string {
        $browser = "Unknown Browser";
        $browserArray = [
            '/msie/i'      => 'Internet Explorer',
            '/firefox/i'   => 'Firefox',
            '/safari/i'    => 'Safari',
            '/chrome/i'    => 'Chrome',
            '/edge/i'      => 'Edge',
            '/opera/i'     => 'Opera',
            '/mobile/i'    => 'Mobile Browser'
        ];

        foreach ($browserArray as $regex => $value) {
            if (preg_match($regex, $userAgent)) {
                $browser = $value;
                break;
            }
        }
        return $browser;
    }
}
