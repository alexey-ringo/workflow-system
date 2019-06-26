npm install vue-router vue-axios --save
npm install vue-axios --save



composer require laravel/passport
php artisan migrate:refresh
php artisan passport:install

Encryption keys generated successfully.
Personal access client created successfully.
Client ID: 1
Client secret: bhusYCDNnqYmxp1L6rRcNpsPRgwxseYK5kCMxAdu
Password grant client created successfully.
Client ID: 2
Client secret: jtkVlmNXW1YR5LBUJivSDsCxuNgAjaCBujNCirx6

php artisan make:model Models/Group -m                                        
php artisan make:model Models/Role -m
php artisan make:model Models/Permission -m                                   
$ php artisan make:migration create_permission_role_table --create=permission_role
php artisan make:migration create_group_mission_table --create=group_mission  
php artisake:migration create_group_user_table --create=group_user        
php artisan make:migration create_role_user_table --create=role_user          
php artisan make:model Models/Task -m                                         
php artisan make:model Models/Comment -m    

 :value="permission.id"
                    :checked="booleanValue"  
                    v-on:input="checkboxVal = $event.target.value"
                    
 <div class="form-group" v-if="(!role.all_permissions.length == 0)">
          <div class="col-sm-offset-2 col-sm-10">
            <div class="form-check" v-for="permission in role.all_permissions" :key="permission.id">
              <input type="checkbox" class="form-check-input"
                   v-bind:value="permission.id"
                   v-model="checkPermission"
                   >
              <label class="form-check-label" for="permissionCheckBox">{{ permission.name }}</label>
            </div>
          </div>
          
        </div>
        
        
<div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <div class="form-check" v-for="permission in role.all_permissions" :key="permission.id">
              <input type="checkbox" class="form-check-input"
                   v-bind:value="permission.id"
                   v-model="role.permissions"
                   >
              <label class="form-check-label" for="permissionCheckBox">{{ permission.name }}</label>
            </div>
          </div>
          
        </div>         

