<template>
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="../../index3.html" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li>
    </ul>

    <!-- SEARCH FORM -->
    <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fa fa-search"></i>
          </button>
        </div>
      </div>
    </form>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fa fa-comments-o"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="" alt="User Avatar" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Brad Diesel
                  <span class="float-right text-sm text-danger"><i class="fa fa-star"></i></span>
                </h3>
                <p class="text-sm">Call me whenever you can...</p>
                <p class="text-sm text-muted"><i class="fa fa-clock-o mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  John Pierce
                  <span class="float-right text-sm text-muted"><i class="fa fa-star"></i></span>
                </h3>
                <p class="text-sm">I got your message bro</p>
                <p class="text-sm text-muted"><i class="fa fa-clock-o mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Nora Silvester
                  <span class="float-right text-sm text-warning"><i class="fa fa-star"></i></span>
                </h3>
                <p class="text-sm">The subject goes here</p>
                <p class="text-sm text-muted"><i class="fa fa-clock-o mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li>
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fa fa-bell-o"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fa fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fa fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fa fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#">
          <i class="fa fa-th-large"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="../../index3.html" class="brand-link">
      <img src=""
           alt="AdminLTE Logo"
           class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ loggedUserName2 }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          
          <li v-if="permissionsView['customer_find']" class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-dashboard"></i>
              <p>
                Прием обращений
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link class="nav-link" :to="{ name: 'search-customer'}"><i class="fa fa-circle-o nav-icon"></i><p>Поиск по клиенту</p></router-link>
              </li>
              <li class="nav-item">
                <router-link class="nav-link" :to="{ name: 'search-customer'}"><i class="fa fa-circle-o nav-icon"></i><p>Поиск по договору</p></router-link>
              </li>
            </ul>
          </li>     
          
          <li v-if="permissionsView['task_index']" class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-dashboard"></i>
              <p>
                Рабочий кабинет
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link class="nav-link" :to="{ name: 'tasks'}"><i class="fa fa-circle-o nav-icon"></i><p>Мои задачи</p></router-link>
              </li>
            </ul>
          </li>
          
          <li v-if="permissionsView['customer_index']" class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-dashboard"></i>
              <p>
                Клиенты
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link class="nav-link" :to="{ name: 'customers'}"><i class="fa fa-circle-o nav-icon"></i><p>Все клиенты</p></router-link>
              </li>
              <li v-if="permissionsView['customer_store']" class="nav-item">
                <router-link class="nav-link" :to="{ name: 'customer-create'}"><i class="fa fa-circle-o nav-icon"></i><p>Новый клиент</p></router-link>
              </li>
            </ul>
          </li>
          
          <li v-if="permissionsView['contract_index']" class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-dashboard"></i>
              <p>
                Контракты
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link class="nav-link" :to="{ name: 'contracts'}"><i class="fa fa-circle-o nav-icon"></i><p>Все контракты</p></router-link>
              </li>
            </ul>
          </li>
          
          <li v-if="permissionsView['tariff_index']" class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-dashboard"></i>
              <p>
                Тарифы
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link class="nav-link" :to="{ name: 'tariffs'}"><i class="fa fa-circle-o nav-icon"></i><p>Все тарифы</p></router-link>
              </li>
              <li v-if="permissionsView['tariff_store']" class="nav-item">
                <router-link class="nav-link" :to="{ name: 'tariff-create'}"><i class="fa fa-circle-o nav-icon"></i><p>Новый тариф</p></router-link>
              </li>
            </ul>
          </li>
          
          <li v-if="permissionsView['user_index']" class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-dashboard"></i>
              <p>
                Пользователи
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link class="nav-link" :to="{ name: 'users'}"><i class="fa fa-circle-o nav-icon"></i><p>Все пользователи</p></router-link>
              </li>
              <li v-if="permissionsView['user_store']" class="nav-item">
                <router-link class="nav-link" :to="{ name: 'user-create'}"><i class="fa fa-circle-o nav-icon"></i><p>Новый пользователь</p></router-link>
              </li>
            </ul>
          </li>
          
          <li v-if="permissionsView['role_index']" class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-dashboard"></i>
              <p>
                Политики безопасности
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link class="nav-link" :to="{ name: 'roles'}"><i class="fa fa-circle-o nav-icon"></i><p>Все политики</p></router-link>
              </li>
              <li v-if="permissionsView['role_store']" class="nav-item">
                <router-link class="nav-link" :to="{ name: 'role-create'}"><i class="fa fa-circle-o nav-icon"></i><p>Новая политика</p></router-link>
              </li>
            </ul>
          </li>
          
          <li v-if="permissionsView['permission_index']" class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-dashboard"></i>
              <p>
                Разрешения операций
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link class="nav-link" :to="{ name: 'permissions'}"><i class="fa fa-circle-o nav-icon"></i><p>Все разрешения</p></router-link>
              </li>
              <li v-if="permissionsView['permission_store']" class="nav-item">
                <router-link class="nav-link" :to="{ name: 'permission-create'}"><i class="fa fa-circle-o nav-icon"></i><p>Новое разрешение</p></router-link>
              </li>
            </ul>
          </li>
          
          <li v-if="permissionsView['group_index']" class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-dashboard"></i>
              <p>
                Рабочие группы
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link class="nav-link" :to="{ name: 'groups'}"><i class="fa fa-circle-o nav-icon"></i><p>Все группы</p></router-link>
              </li>
              <li v-if="permissionsView['group_store']" class="nav-item">
                <router-link class="nav-link" :to="{ name: 'group-create'}"><i class="fa fa-circle-o nav-icon"></i><p>Новая группа</p></router-link>
              </li>
            </ul>
          </li>
          
          <li v-if="permissionsView['process_index']" class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-dashboard"></i>
              <p>
                Процессы
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link class="nav-link" :to="{ name: 'processes'}"><i class="fa fa-circle-o nav-icon"></i><p>Все процессы</p></router-link>
              </li>
              <li v-if="permissionsView['process_store']" class="nav-item">
                <router-link class="nav-link" :to="{ name: 'process-create'}"><i class="fa fa-circle-o nav-icon"></i><p>Новый процесс</p></router-link>
              </li>
            </ul>
          </li>
          
          <li v-if="permissionsView['route_index']" class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-dashboard"></i>
              <p>
                Маршруты
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link class="nav-link" :to="{ name: 'routes'}"><i class="fa fa-circle-o nav-icon"></i><p>Все маршруты</p></router-link>
              </li>
              <li v-if="permissionsView['route_store']" class="nav-item">
                <router-link class="nav-link" :to="{ name: 'route-create'}"><i class="fa fa-circle-o nav-icon"></i><p>Новый маршрут</p></router-link>
              </li>
            </ul>
          </li>
          
          <li v-if="permissionsView['telegram_view']" class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-dashboard"></i>
              <p>
                Telegram Bot
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link class="nav-link" :to="{ name: 'bot-status'}"><i class="fa fa-circle-o nav-icon"></i><p>Статус</p></router-link>
              </li>
              <li v-if="permissionsView['telegram_edit']" class="nav-item">
                <router-link class="nav-link" :to="{ name: 'bot-setting'}"><i class="fa fa-circle-o nav-icon"></i><p>Настройки</p></router-link>
              </li>
            </ul>
          </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Data Tables</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Data Tables</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
            
            <router-view></router-view>

        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 3.0.0-alpha
    </div>
    <strong>Copyright &copy; 2014-2018 <a href="http://adminlte.io">AdminLTE.io</a>.</strong> All rights
    reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
      <router-link :to="{ name: 'logout' }" class="nav-link" v-if="isLoggedIn">Выход</router-link>
    </div>
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
</template>


<script>
    //import Products from './lteitems/Products.vue';
    //import Orders from './lteitems/Orders.vue';
    //import Purchases from './lteitems/Purchases.vue';
    
    export default {
        components: {
            //'products': Products,
            //'orders': Orders,
            //'purchases': Purchases,
        },
        data(){
            return {
                isLoggedIn : null,
                name : null,
                loggedUser : {},
                loggedUserName2: '',
                permissionsView: []
            }
        },
        mounted() {
            //console.log('AdminLte Component mounted.');
            this.isLoggedIn = localStorage.getItem('jwt');
            this.name = localStorage.getItem('user');
            this.axios.defaults.headers.common['Content-Type'] = 'application/json'
            this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.isLoggedIn
            
            this.getLoggedUser();
        },
        methods: {
          getLoggedUser() {
            let uri = '/api/logged-user';
            this.axios.get(uri)
            	.then((response) => {
            	  if(response.data.data) {
                	this.loggedUser = response.data.data;
                	this.loggedUserName2 = response.data.data.name;
                	
                	this.getPermissionsView();
            	  }
            	  else if (response.data.message) {
                  this.message = response.data.message;
                  swal("Ошибка", this.message, "error");
                }
                else {
                  swal("Ошибка", "Нет ответа от сервера при первоначальном входе в систему", "error");
                }
              })
              .catch(error => {
                if(error.response) {
                  if(error.response.data.message) {
                    if(error.response.status == 401) {
                      if (localStorage.getItem('jwt')) {
                        localStorage.removeItem('jwt');
                        this.$router.push({name: 'login'});
                      }
                    }
                    else {
                      swal('Ошибка - ' + error.response.status, error.response.data.message, "error");
                    }
                  }         
                }
                else if(error.request) {
                }
                else {
                  swal('Ошибка', "Внутренняя ошибка сервера", "error");
                }
              });
          },
          getPermissionsView() {
            let uri = '/api/permissions-menu';
            this.axios.get(uri)
              .then((response) => {
                if(response.data.data) {
                  this.permissionsView = response.data.data;
                }
            	  else if (response.data.message) {
                  this.message = response.data.message;
                  swal("Ошибка", this.message, "error");
                }
                else {
                  swal("Ошибка", "Нет ответа от сервера при первоначальном доступе к списку разрешений доступа пользователей", "error");
                }        
              })
              .catch(error => {
                if(error.response) {
                  if(error.response.data.message) {
                    if(error.response.status == 401) {
                      if (localStorage.getItem('jwt')) {
                        localStorage.removeItem('jwt');
                        this.$router.push({name: 'login'});
                      }
                    }
                    else {
                      swal('Ошибка - ' + error.response.status, error.response.data.message, "error");
                    }
                  }         
                }
                else if(error.request) {
              }
              else {
                swal('Ошибка', "Внутренняя ошибка сервера", "error");
              }
            });
          }
        },
        computed: {
          loggedUserName() {
            return this.loggedUser.name;
          }
        },
        beforeRouteEnter (to, from, next) { 
            if (!localStorage.getItem('jwt')) {
                return next({name: 'login'});
            }

            next();
        }
        
    }
</script>