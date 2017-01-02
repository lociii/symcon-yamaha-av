<?php

class Yamaha
{
    protected $timeout = 30;

    protected $value_on = 'On';
    protected $value_off = 'Off';
    protected $value_standby = 'Standby';

    protected $xml_get = '<YAMAHA_AV cmd="GET"><Main_Zone>%s</Main_Zone></YAMAHA_AV>';
    protected $xml_put = '<YAMAHA_AV cmd="PUT"><Main_Zone>%s</Main_Zone></YAMAHA_AV>';

    protected $xml_status = '<Basic_Status>GetParam</Basic_Status>';
    protected $xml_power = '<Power_Control><Power>%s</Power></Power_Control>';
    protected $xml_mute = '<Volume><Mute>%s</Mute></Volume>';
    protected $xml_volume = '<Volume><Lvl><Val>%s</Val><Exp>1</Exp><Unit>dB</Unit></Lvl></Volume>';
    protected $xml_scene = '<Scene><Scene_Sel>Scene %s</Scene_Sel></Scene>';

    public function __construct($ip, $port = 80)
    {
        $this->ip = $ip;
        $this->port = $port;
    }

    private function _send($xml)
    {
        $ch = curl_init();
        $url = 'http://' . $this->ip . ':' . $this->port . '/YamahaRemoteControl/ctrl';
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type' => 'text/xml'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    private function _xml_get($xml)
    {
        return $this->_send(sprintf($this->xml_get, $xml));
    }

    private function _xml_put($xml)
    {
        return $this->_send(sprintf($this->xml_put, $xml));
    }

    public function GetStatus()
    {
        $data = $this->_xml_get($this->xml_status);
        $xml = simplexml_load_string($data);
        return array(
            'power' => $xml->Main_Zone->Basic_Status->Power_Control->Power == $this->value_on,
            'mute' => $xml->Main_Zone->Basic_Status->Volume->Mute == $this->value_on,
            'volume' => (int)$xml->Main_Zone->Basic_Status->Volume->Lvl->Val / 10,
            'scene' => (int)$xml->Main_Zone->Basic_Status->Input->Input_Sel_Item_Info->Src_Number,
        );
    }

    public function PowerOn()
    {
        return $this->_xml_put(sprintf($this->xml_power, $this->value_on));
    }

    public function PowerOff()
    {
        return $this->_xml_put(sprintf($this->xml_power, $this->value_standby));
    }

    public function MuteOn()
    {
        return $this->_xml_put(sprintf($this->xml_mute, $this->value_on));
    }

    public function MuteOff()
    {
        return $this->_xml_put(sprintf($this->xml_mute, $this->value_off));
    }

    public function VolumeIncrease()
    {
        return $this->_xml_put(sprintf($this->xml_volume, ($this->getStatus()['volume'] + 1) * 10));
    }

    public function VolumeDecrease()
    {
        return $this->_xml_put(sprintf($this->xml_volume, ($this->getStatus()['volume'] - 1) * 10));
    }

    public function sceneSet($scene)
    {
        return $this->_xml_put(sprintf($this->xml_scene, $scene));
    }
}
