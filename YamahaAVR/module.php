<?php

include_once(__DIR__ . '/../shared/BaseModule.php');
include_once(__DIR__ . '/Yamaha.php');

class YamahaAVR extends IPSBaseModule
{
    protected $config = array('yavr_ip', 'yavr_interval');
    private $yamaha;

    public function Create()
    {
        parent::Create();

        $this->RegisterPropertyString('yavr_ip', '');
        $this->RegisterPropertyInteger('yavr_interval', 1);

        $this->CreateVarProfileDecibel();
        $this->CreateVarProfileYamahaScene();

        $this->RegisterTimer('update', $this->ReadPropertyInteger('yavr_interval'), 'LOCIYAVR_Update($_IPS[\'TARGET\']);');
    }

    protected function OnConfigValid()
    {
        $this->SetTimerInterval('update', $this->ReadPropertyInteger('yavr_interval') * 1000 * 60);
        $this->MaintainVariable('power', 'Power', 0, '', 10, true);
        $this->MaintainVariable('mute', 'Mute', 0, '', 20, true);
        $this->MaintainVariable('volume', 'Volume', 2, 'LOCI.Decibel', 30, true);
        $this->MaintainVariable('scene', 'Scene', 1, 'LOCI.YamahaScene', 40, true);
        $this->SetStatus(102);
    }

    protected function OnConfigInvalid()
    {
        $this->SetTimerInterval('update', 0);
        $this->SetStatus(104);
    }

    private function CreateVarProfileDecibel()
    {
        if (!IPS_VariableProfileExists('LOCI.Decibel')) {
            IPS_CreateVariableProfile('LOCI.Decibel', 2);
            IPS_SetVariableProfileText('LOCI.Decibel', '', ' dB');
            IPS_SetVariableProfileValues('LOCI.Decibel', -100, 100, 0.5);
            IPS_SetVariableProfileDigits('LOCI.Decibel', 1);
            IPS_SetVariableProfileIcon('LOCI.Decibel', 'Decibel');
        }
    }

    private function CreateVarProfileYamahaScene()
    {
        if (!IPS_VariableProfileExists('LOCI.YamahaScene')) {
            IPS_CreateVariableProfile('LOCI.YamahaScene', 1);
            IPS_SetVariableProfileText('LOCI.YamahaScene', 'Scene ', '');
            IPS_SetVariableProfileValues('LOCI.YamahaScene', 1, 4, 1);
            IPS_SetVariableProfileDigits('LOCI.YamahaScene', 0);
            IPS_SetVariableProfileIcon('LOCI.YamahaScene', 'Scene');
        }
    }

    private function GetYamahaClient()
    {
        if ($this->yamaha == null) {
            $this->yamaha = new Yamaha($this->ReadPropertyString('yavr_ip'));
        }
        return $this->yamaha;
    }

    public function Update()
    {
        $status = $this->GetYamahaClient()->GetStatus();
        SetValue($this->GetIDForIdent('power'), $status['power']);
        SetValue($this->GetIDForIdent('mute'), $status['mute']);
        SetValue($this->GetIDForIdent('volume'), $status['volume']);
        SetValue($this->GetIDForIdent('scene'), $status['scene']);
    }

    public function PowerOn()
    {
        $this->GetYamahaClient()->PowerOn();
        SetValue($this->GetIDForIdent('power'), true);
    }

    public function PowerOff()
    {
        $this->GetYamahaClient()->PowerOff();
        SetValue($this->GetIDForIdent('power'), false);
    }

    public function MuteOn()
    {
        $this->GetYamahaClient()->MuteOn();
        SetValue($this->GetIDForIdent('mute'), true);
    }

    public function MuteOff()
    {
        $this->GetYamahaClient()->MuteOff();
        SetValue($this->GetIDForIdent('mute'), false);
    }

    public function VolumeIncrease()
    {
        $this->GetYamahaClient()->VolumeIncrease();
    }

    public function VolumeDecrease()
    {
        $this->GetYamahaClient()->VolumeDecrease();
    }

    public function sceneSet1()
    {
        $this->GetYamahaClient()->sceneSet(1);
        SetValue($this->GetIDForIdent('scene'), 1);
    }

    public function sceneSet2()
    {
        $this->GetYamahaClient()->sceneSet(2);
        SetValue($this->GetIDForIdent('scene'), 2);
    }

    public function sceneSet3()
    {
        $this->GetYamahaClient()->sceneSet(3);
        SetValue($this->GetIDForIdent('scene'), 3);
    }

    public function sceneSet4()
    {
        $this->GetYamahaClient()->sceneSet(4);
        SetValue($this->GetIDForIdent('scene'), 4);
    }
}
