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

import Groups from './components/process/Groups';
import GroupUpdate from './components/process/GroupUpdate';
import GroupCreate from './components/process/GroupCreate';

import Processes from './components/process/Processes';
import ProcessUpdate from './components/process/ProcessUpdate';
import ProcessCreate from './components/process/ProcessCreate';

import Contracts from './components/contract/Contracts';
//import ContractUpdate from './components/contract/ContractUpdate';
//import ContractCreate from './components/contract/ContractCreate';

import Customers from './components/customer/Customers';
import CustomerUpdate from './components/customer/CustomerUpdate';
import CustomerCreate from './components/customer/CustomerCreate';

import Tariffs from './components/tariff/Tariffs';
import TariffUpdate from './components/tariff/TariffUpdate';
import TariffCreate from './components/tariff/TariffCreate';

import Routes from './components/process/Routes';
import RouteUpdate from './components/process/RouteUpdate';
import RouteCreate from './components/process/RouteCreate';

import Tasks from './components/task/Tasks';
import TaskUpdate from './components/task/TaskUpdate';
import SearchCustomer from './components/task/SearchCustomer';
import ContractsForCustomer from './components/task/ContractsForCustomer';
import CreateTaskForExistsContract from './components/task/CreateTaskForExistsContract';
import CustomerNotFound from './components/task/CustomerNotFound';


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
            
                { path: 'processes',  name: 'processes', component: Processes },
                { path: 'process/:id',  name: 'process-update', component: ProcessUpdate },
                { path: 'process-new',  name: 'process-create', component: ProcessCreate },
                
                { path: 'routes',  name: 'routes', component: Routes },
                { path: 'route/:id',  name: 'route-update', component: RouteUpdate },
                { path: 'route-new',  name: 'route-create', component: RouteCreate },
                
                { path: 'customers',  name: 'customers', component: Customers },
                { path: 'customer/:id',  name: 'customer-update', component: CustomerUpdate },
                { path: 'customer-new',  name: 'customer-create', component: CustomerCreate },
                
                { path: 'tariffs',  name: 'tariffs', component: Tariffs },
                { path: 'tariff/:id',  name: 'tariff-update', component: TariffUpdate },
                { path: 'tariff-new',  name: 'tariff-create', component: TariffCreate },
                
                { path: 'contracts',  name: 'contracts', component: Contracts },
            //    { path: 'contract/:id',  name: 'contract-update', component: ContractUpdate },
            //    { path: 'contract-new',  name: 'contract-create', component: ContractCreate },
                
            //    { path: 'prices',  name: 'prices', component: Prices },
            //    { path: 'price/:id',  name: 'price-update', component: PriceUpdate },
            //    { path: 'price-new',  name: 'price-create', component: PriceCreate },
            
                { path: 'tasks',  name: 'tasks', component: Tasks },
                { path: 'new',  name: 'search-customer', component: SearchCustomer, 
                    children: [
                        {
                            path: 'customers/:customid',  
                            name: 'contracts-for-customer', 
                            component: ContractsForCustomer 
                        },
                        { 
                            path: 'customer-notfound',  
                            name: 'customer-not-found', 
                            component: CustomerNotFound 
                            
                        },
                        //{ 
                        //    path: 'contracts/:contractid',  
                        //    name: 'create-task-for-exists-contract',
                        //    component: CreateTaskForExistsContract, 
                        //},
                        
                    ]
                },
                { path: 'new/contracts/:contractid',  name: 'create-task-for-exists-contract', component: CreateTaskForExistsContract },
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