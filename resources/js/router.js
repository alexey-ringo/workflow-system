import VueRouter from 'vue-router';

//Импорт компонента

import Dashboard from './components/Dashboard';
import Login from './components/auth/Login';
import Logout from './components/auth/Logout';

import Users from './components/user/Users';
import UserUpdate from './components/user/UserUpdate';
import UserCreate from './components/user/UserCreate';

import Roles from './components/permission/Roles';
import RoleUpdate from './components/permission/RoleUpdate';
import RoleCreate from './components/permission/RoleCreate';

import Permissions from './components/permission/Permissions';
import PermissionUpdate from './components/permission/PermissionUpdate';
import PermissionCreate from './components/permission/PermissionCreate';

import Groups from './components/group/Groups';
import GroupUpdate from './components/group/GroupUpdate';
import GroupCreate from './components/group/GroupCreate';

import Missions from './components/group/Missions';
import MissionUpdate from './components/group/MissionUpdate';
import MissionCreate from './components/group/MissionCreate';

import Tasks from './components/task/Tasks';
import TaskUpdate from './components/task/TaskUpdate';
import TaskCreate from './components/task/TaskCreate';

import CommentDetails from './components/task/CommentDetails';
//import CommentCreate from './components/task/CommentCreate';

import BotSetting from './components/telegram/BotSetting';
import BotStatus from './components/telegram/BotStatus';





//Экспорт объекта VueRouter (который импортировали выше)
//В него передаем Литерал-Объект
export default new VueRouter({
    //В массиве routes в виде объектов будут перечислены маршруты (пути и связанные с ними конпоненты)
    routes : [
        
        {
            path: '/login',
            name: 'login',
            component: Login,
        },
        {
            path: '/logout',
            name: 'logout',
            component: Logout,
        },
        {
            path: '/',
            name: 'dashboard',
            component : Dashboard,
            
            children: [
            
                { path: 'users',  name: 'users', component: Users },
                { path: 'user/:id',  name: 'user-update', component: UserUpdate },
                { path: 'user-new',  name: 'user-create', component: UserCreate },
            
                { path: 'roles',  name: 'roles', component: Roles },
                { path: 'role/:id',  name: 'role-update', component: RoleUpdate },
                { path: 'role-new',  name: 'role-create', component: RoleCreate },
            
                { path: 'permissions',  name: 'permissions', component: Permissions },
                { path: 'permission/:id',  name: 'permission-update', component: PermissionUpdate },
                { path: 'permission-new',  name: 'permission-create', component: PermissionCreate },
            
                { path: 'groups',  name: 'groups', component: Groups },
                { path: 'group/:id',  name: 'group-update', component: GroupUpdate },
                { path: 'group-new',  name: 'group-create', component: GroupCreate },
            
                { path: 'missions',  name: 'missions', component: Missions },
                { path: 'mission/:id',  name: 'mission-update', component: MissionUpdate },
                { path: 'mission-new',  name: 'mission-create', component: MissionCreate },
            
                { path: 'tasks',  name: 'tasks', component: Tasks },
                { path: 'task-new',  name: 'task-create', component: TaskCreate },
                { path: 'task/:id',  name: 'task-update', component: TaskUpdate, 
                    //children: [
                            //{ path: 'comments',  name: 'comments', component: Comments },
                        //{ path: 'comment/:commid',  name: 'comment-details', component: CommentDetails }
                            //{ path: 'comment-new',  name: 'comment-create', component: CommentCreate },
                    //]
                },
                
                { path: 'comment/:commid',  name: 'comment-details', component: CommentDetails },
            
                { path: 'bot-setting',  name: 'bot-setting', component: BotSetting },
                { path: 'bot-status',  name: 'bot-status', component: BotStatus },
            ]
        },
    ],
    //Запись всех перемещений пользователя по переходам
    mode: 'history'
    
});