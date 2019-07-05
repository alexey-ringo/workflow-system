<?php

namespace App\Services;

use App\Models\Mission;

class MissionService
{
    public function __construct()
    {
        
    }
    public function getMissionName(int $sequence): ?string
    {
        $mission = Mission::where('sequence', $sequence)->first();
        return $mission ? $mission->name : null;
    }
    
    public function getMissionId(int $sequence): ?int
    {
        $mission = Mission::where('sequence', $sequence)->first();
        return $mission ? $mission->id : null;
    }
    
    public function getPrevMissionName(int $sequence): string
    {
        $mission = Mission::where('sequence', $sequence - 1)->first();
        return $mission ? $mission->name : $this->getMissionName($sequence);
    }
    
    public function getNextMissionName(int $sequence): string
    {
        $mission = Mission::where('sequence', $sequence + 1)->first();
        return $mission ? $mission->name : $this->getMissionName($sequence);
    }
    
    
    public function getPrevMissionId(int $sequence): int
    {
        $mission = Mission::where('sequence', $sequence - 1)->first();
        return $mission ? $mission->id : $this->getMissionId($sequence);
    }
    
    public function getNextMissionId(int $sequence): int
    {
        $mission = Mission::where('sequence', $sequence + 1)->first();
        return $mission ? $mission->id : $this->getMissionId($sequence);
    }
    
    
    public function isSequenceFirst(int $sequence): bool
    {
        if(!Mission::where('sequence', $sequence - 1)->first()) {
            return true;
        }
        return false;
    }
    
    public function isSequenceLast(int $sequence): bool
    {
        if(!Mission::where('sequence', $sequence + 1)->first()) {
            return true;
        }
        return false;
    }
    
    public function isSequenceFinal(int $sequence): bool
    {
        $mission = Mission::where('sequence', $sequence)->first();
        return $mission ? $mission->is_final : false;
    }
    
    public function getSequence(int $missionId): int {
        $mission = Mission::find($missionId);
        return $mission ? $mission->sequence : null; 
    }
    
}