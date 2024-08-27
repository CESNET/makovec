<?php

namespace App\Services;

class MacService
{
    public function clean(?string $mac = null): ?string
    {
        if (is_null($mac)) {
            return null;
        }

        $stripped = preg_replace('/(\W)/', '', $mac);
        $coloned = preg_replace('/(..)(..)(..)(..)(..)(..)/', '\1:\2:\3:\4:\5:\6', $stripped);
        $uppercased = strtoupper($coloned);

        return $uppercased;
    }

    public function isReserved(string $mac): bool
    {
        // OUI 01:00:5E:(00:00:00-7f:ff:ff) - Used for IPV4 Multicast and MLPS Multicast.
        if (preg_match('/^01005e[0-7][0-9a-f][0-9a-f]{2}[0-9a-f]{2}$/', $mac)) {
            return true;
        }

        // OUI 00:00:5E:(00:01:00 – 00:01:FF) - Used for Virtual Router Redundancy Protocol (VRRP) IPV4
        if (preg_match('/^00005e0001[0-9a-f]{2}$/', $mac)) {
            return true;
        }

        // OUI 00:00:5E:(00:02:00 – 00:02:FF) - Used for Virtual Router Redundancy Protocol (VRRP) IPV6
        if (preg_match('/^00005e0002[0-9a-f]{2}$/', $mac)) {
            return true;
        }

        // OUI 33:33:00 – 33:33:FF - Reserved for IPV6 Multicast
        if (preg_match('/^3333[0-9a-f]{2}[0-9a-f]{2}[0-9a-f]{2}[0-9a-f]{2}$/', $mac)) {
            return true;
        }

        // OUI CF:00:00 – CF:FF:FF - Reserved by IANA for PPP(Point to Point Protocol)
        if (preg_match('/^cf[0-9a-f]{2}[0-9a-f]{2}[0-9a-f]{2}[0-9a-f]{2}[0-9a-f]{2}$/', $mac)) {
            return true;
        }

        // OUI 00:00:5E (00:00:00 - 00:00:FF) - Requires IESG Ratification for allocation.
        if (preg_match('/^00005e0000[0-9a-f]{2}$/', $mac)) {
            return true;
        }

        return false;
    }
}
