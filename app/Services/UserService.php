<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    public function __construct()
    {
        
    }
    
    public function hasSequences(User $user): array
    {
        $groupsWithMissions = $user->with('groups.missions')->get()->toArray();
        
        $sequences = [];
        
        foreach($groupsWithMissions as $userIndex => $userValue) {
            if($userIndex == 0) {
                foreach($userValue as $groupsKey => $groupsValue) {
                    if($groupsKey == 'groups') {
                        foreach($groupsValue as /*$groupKey => */$groupValue) {
                            //if($groupKey == 'missions') {
                                foreach($groupValue as $missionsKey => $missionsValue) {
                                    if(is_array($missionsValue)) {
                                        foreach($missionsValue as $missionKey => $missionValue) {
                                            if(is_array($missionValue)) {
                                                foreach($missionValue as $finalMissionKey => $finalMissionValue) {
                                                    if($finalMissionKey == 'sequence') {
                                                        $sequences[] = $finalMissionValue;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            //}
                        }
                    }    
                }
            }
        }
        
        //$uniq = array_unique($sequences); 
        //$diff  = array_diff_assoc($sequences, $uniq); 
        
        //return array_diff($sequences, $diff); 
        return array_unique($sequences);
    }
    
    
    public function canFirstCreate(User $user): bool 
    {
        $hasProcessesWithFirst = $user->with('groups.processes')
                        ->whereHas('groups.processes', function($q) {
                                $q->where('sequence', 1);
                        })->get();
        
        //$hasMissions = $this->with(['groups.missions' => function($q) {
        //                        return $q->where(function($q));
        //                }]);
        
        return $hasProcessesWithFirst->isNotEmpty();
    }
    
    public function getUsersByProcess(int $processId): Collection 
    {
        $usersByProcess = User::with('groups.processes')
                        ->whereHas('groups.processes', function($q) use($processId) {
                                $q->where('processes.id', $processId);
                        })->get();
        
        return $usersByProcess;
    }
    
}