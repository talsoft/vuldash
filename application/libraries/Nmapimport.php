<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Nmapimport { 
  public function parsexml($file) {
    $xml = simplexml_load_file($file);

    $hosts = array();
    foreach ($xml->host as $host) 
    {      
      $ports = array();
      foreach ($host->ports->port as $port) 
      {      
        $ports[] = array(
                    'port'        => ''.$port->attributes()['portid'],
                    'state'       => ''.$port->state->attributes()['state'],
                    'cvss'        => '1',
                    'service'     => ''.$port->service->attributes()['name'],
                    'accordance'  => '-',
                    'details'     => ''.$port->service->attributes()['product'] . ' ' . $port->service->attributes()['version'] . ' ' . $port->service->attributes()['extrainfo'],
        );
      }      
      $hosts[] = array(
        'host'  => ''.$host->address['addr'],
        'ports' => $ports,
      );  
    }
    return json_encode(array_values($hosts));
  }
}