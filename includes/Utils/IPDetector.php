<?php
namespace VigyapanamAffiliate\Utils;

class IPDetector {
    private $ip_headers = [
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'REMOTE_ADDR'
    ];

    public function get_client_ip() {
        foreach ($this->ip_headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ip = $_SERVER[$header];
                if (strpos($ip, ',') !== false) {
                    $ips = explode(',', $ip);
                    $ip = trim($ips[0]);
                }
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        return '0.0.0.0';
    }

    public function is_valid_ip($ip) {
        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }

    public function is_private_ip($ip) {
        return !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE);
    }

    public function is_reserved_ip($ip) {
        return !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE);
    }
}