<?php

$router->get('/', 'AuthController@index');
$router->post('/', 'AuthController@login');
$router->post('/logout', 'AuthController@logout');
$router->get('/users', 'UserController@index');
$router->get('/employee', 'EmployeeController@index');
$router->get('/admin', 'AdminController@index');
$router->get('/admin/sales', 'AdminController@sales');
$router->get('/admin/medicines', 'AdminController@medicines');
$router->get('/admin/employees', 'AdminController@employees');
$router->get('/admin/stock', 'AdminController@stock');
$router->get('/admin/medicine-categories', 'AdminController@categories');
$router->post('/admin/medicines/add', 'AdminController@addMedicine');
$router->post('/admin/medicines/edit', 'AdminController@editMedicine');
$router->post('/admin/medicines/delete', 'AdminController@deleteMedicine');
$router->post('/admin/stock/edit', 'AdminController@editStock');
$router->post('/admin/stock/add', 'AdminController@addStock');
$router->post('/admin/stock/delete', 'AdminController@deleteStock');
$router->post('/admin/medicine-categories/add', 'AdminController@addCategory');
$router->post('/admin/medicine-categories/edit', 'AdminController@editCategory');
$router->post('/admin/medicine-categories/delete', 'AdminController@deleteCategory');
$router->post('/admin/employees/delete', 'AdminController@deleteEmployee');
$router->post('/admin/employees/add', 'AdminController@addEmployee');
$router->get('/employee/sales', 'EmployeeController@sales');
$router->get('/employee/pos', 'EmployeeController@pos');
$router->post('/employee/pos/place-order', 'EmployeeController@placeOrder');