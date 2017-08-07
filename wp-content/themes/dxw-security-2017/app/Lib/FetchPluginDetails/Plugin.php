<?php

namespace Dxw\DxwSecurity2017\Lib\FetchPluginDetails;

class Plugin {

    private $getter;

    public function __construct(Getter $getter)
    {
        $this->getter = $getter;
    }

    public function getDetails($slug)
    {
        $response = $this->getter->getPluginInfo($slug);

        if ($response === null) {
            echo(json_encode(array('ok' => false))."\n");
            wp_die();
        }

        else {
            $data = array();
            $bigDesc = $response->sections['description'];
            preg_match('/<p>(.*?)<\/p>/', $bigDesc, $m);
            if ($m) {
                $data['description'] = $m[0];
            }
            else {
                $data['description'] = '';
            }
            $data['ok'] = true;
            $data['slug'] = $slug;
            $data['name'] = $response->name;
            $data['version'] = $response->version;
            $data['author'] = implode(', ', array_keys($response->contributors));
            $data['link'] = 'http://wordpress.org/plugins/'.$slug.'/';

            return $data;
        }
    }

}
