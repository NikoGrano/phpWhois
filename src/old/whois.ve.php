<?php

declare(strict_types=1);

/**
 *
 * NOTICE OF LICENSE
 *
 * This source file is released under GNU General Public License v2.
 *
 * @copyright 1999-2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright 2005-2014 David Saez
 * @copyright 2014-2019 Dmitry Lukashin
 * @copyright 2019-2020 Niko Granö (https://granö.fi)
 *
 */

if (!\defined('__VE_HANDLER__')) {
    \define('__VE_HANDLER__', 1);
}

require_once 'whois.parser.php';

class ve_handler
{
    public function parse($data_str, $query)
    {
        $items = [
            'owner'          => 'Titular:',
            'domain.name'    => 'Nombre de Dominio:',
            'admin'          => 'Contacto Administrativo',
            'tech'           => 'Contacto Tecnico',
            'billing'        => 'Contacto de Cobranza:',
            'domain.created' => 'Fecha de Creacion:',
            'domain.changed' => 'Ultima Actualizacion:',
            'domain.expires' => 'Fecha de Vencimiento:',
            'domain.status'  => 'Estatus del dominio:',
            'domain.nserver' => 'Servidor(es) de Nombres de Dominio',
        ];

        $r = [];
        $r['regrinfo'] = get_blocks($data_str['rawdata'], $items);

        if (!isset($r['regrinfo']['domain']['created']) || \is_array($r['regrinfo']['domain']['created'])) {
            $r['regrinfo'] = ['registered' => 'no'];

            return $r;
        }

        $dns = [];

        foreach ($r['regrinfo']['domain']['nserver'] as $nserv) {
            if ('-' === $nserv[0]) {
                $dns[] = $nserv;
            }
        }

        $r['regrinfo']['domain']['nserver'] = $dns;
        $r['regrinfo'] = get_contacts($r['regrinfo']);
        $r['regyinfo'] = [
            'referrer'  => 'http://registro.nic.ve',
            'registrar' => 'NIC-Venezuela - CNTI',
        ];

        return $r;
    }
}
