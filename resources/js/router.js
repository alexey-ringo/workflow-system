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
            // UserHome will be rendered inside User's <router-view>
            // when /user/:id is matched
            //{ path: '',  component: Adminlte },
				
            // UserProfile will be rendered inside User's <router-view>
            // when /user/:id/profile is matched
            
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
            
            // UserPosts will be rendered inside User's <router-view>
            // when /user/:id/posts is matched
            
        //    { path: 'orders', name: 'adminorders', component: Orders },
            
            // UserPosts will be rendered inside User's <router-view>
            // when /user/:id/posts is matched
            
        //    { path: 'purchases', name: 'adminpurchases', component: Purchases },
            ]
        },
        
        
        
        
    ],
    //Запись всех перемещений пользователя по переходам
    mode: 'history'
    
});