npm install vue-router vue-axios --save
npm install vue-axios --save



composer require laravel/passport
php artisan migrate:refresh
php artisan passport:install


php artisan make:model Models/Group -m                                        
php artisan make:model Models/Role -m
php artisan make:model Models/Permission -m                                   
$ php artisan make:migration create_permission_role_table --create=permission_role
php artisan make:migration create_group_mission_table --create=group_mission  
php artisake:migration create_group_user_table --create=group_user        
php artisan make:migration create_role_user_table --create=role_user          
php artisan make:model Models/Task -m                                         
php artisan make:model Models/Comment -m    

git rm -r --cached .c9
git add .
git commit -m "remove .c9"
git push -u origin master

+----+----------------------------------------------------------+----------+----------+----------+---------------------+---------------------+
| id | name                                                     | sequence | is_super | is_final | created_at          | updated_at          |
+----+----------------------------------------------------------+----------+----------+----------+---------------------+---------------------+
|  1 | Прием заявки                                             |        1 |        0 |        0 | 2019-07-04 09:09:48 | 2019-07-04 09:09:48 |
|  2 | Первичная обработка обращений                            |        2 |        0 |        0 | 2019-07-04 09:10:23 | 2019-07-04 09:10:23 |
|  4 | Обработка заявки                                         |        3 |        0 |        0 | 2019-07-04 09:11:22 | 2019-07-04 09:11:22 |
|  5 | Контроль качества                                        |        4 |        0 |        1 | 2019-07-04 09:11:35 | 2019-07-04 09:12:22 |
|  6 | Супервайзинг процессов                                   |        0 |        0 |        0 | 2019-07-04 09:12:15 | 2019-07-04 09:12:15 |
+----+----------------------------------------------------------+----------+----------+----------+---------------------+---------------------+
5 rows in set (0.01 sec)


