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

/* TODO:
   - whois - converter para http://domaininfo.com/idn_conversion.asp punnycode antes de efectuar a pesquisa
   - o punnycode deveria fazer parte dos resultados fazer parte dos resultados!
*/

if (!\defined('__PT_HANDLER__')) {
    \define('__PT_HANDLER__', 1);
}

require_once 'whois.parser.php';

class pt_handler
{
    public function parse($data, $query)
    {
        $items = [
                    'domain.name' 		   => ' / Domain Name:',
                    'domain.created' 	 => 'Data de registo / Creation Date (dd/mm/yyyy):',
                    'domain.nserver.' 	=> 'Nameserver:',
                    'domain.status'	 	 => 'Estado / Status:',
                    'owner'				        => 'Titular / Registrant',
                    'billing'			       => 'Entidade Gestora / Billing Contact',
                    'admin'				        => 'Responsável Administrativo / Admin Contact',
                    'tech'				         => 'Responsável Técnico / Tech Contact',
                    '#'					           => 'Nameserver Information',
                    ];

        $r['regrinfo'] = get_blocks($data['rawdata'], $items);

        if (empty($r['regrinfo']['domain']['name'])) {
            \print_r($r['regrinfo']);
            $r['regrinfo']['registered'] = 'no';

            return $r;
        }

        $r['regrinfo']['domain']['created'] = get_date($r['regrinfo']['domain']['created'], 'dmy');

        if ('ACTIVE' === $r['regrinfo']['domain']['status']) {
            $r['regrinfo'] = get_contacts($r['regrinfo']);
            $r['regrinfo']['registered'] = 'yes';
        } else {
            $r['regrinfo']['registered'] = 'no';
        }

        $r['regyinfo'] = [
            'referrer'  => 'http://www.fccn.pt',
            'registrar' => 'FCCN',
            ];

        return $r;
    }
}
